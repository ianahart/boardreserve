<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Mail\ResetPassword;
use App\Models\User;
use App\Models\PasswordReset;

class ForgotPasswordController extends Controller
{


    public function index()
    {
        return view('forgotpassword.index');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email']
            ]
        );

        $userInput = $request->input('email');

        $user = User::select('id', 'password', 'email', 'name')
            ->where('email', '=', $userInput)
            ->first();

        if (!$user) {
            return redirect()
                ->back()
                ->with('not_found', 'Email address does not exist');
        } else {

            $firstName = explode(' ', $user->name)[0];

            $bytes = random_bytes(20);

            $token = bin2hex($bytes);

            $passwordReset = new PasswordReset();

            $passwordReset->token = $token;

            $passwordReset->email = $user->email;

            $passwordReset->name = $firstName;

            $this->sendEmail($user, $passwordReset);

            $passwordReset->save();



            return redirect('/passwordpending?name=' . $firstName . '&email=' . $user->email . '&token=' . $token);
        }
    }

    private function sendEMail($user, $passwordReset)
    {
        Mail::to($user->email)->send(new ResetPassword($passwordReset));
    }

    public function showPending(Request $request)
    {
        $name = $request->query('name');
        $email = $request->query('email');

        return view(
            'forgotpassword.showpending',
            [

                'name' => $name,
                'email' => $email
            ]
        );
    }

    public function showResetPassword(Request $request)
    {
        return view('forgotpassword.showresetpassword', [

            'token' => $request->query('token')
        ]);
    }

    public function updateResetPassword(Request $request)
    {
        $password = $request->input('password');

        $confirmPassword = $request->input('confirmpassword');

        $token = $request->query('token');

        $request->validate([
            'password' => [
                'required',
                'min:6',
                'max:25'
            ]
        ]);

        if ($password !== $confirmPassword) {

            return redirect()
                ->back()
                ->with(
                    'not_same_password',
                    'Passwords do not match'
                );
        }

        $resetRow = PasswordReset::select('id', 'email', 'token', 'created_at')
            ->where('token', '=', $token)
            ->first();

        $tokenExpiresIn = 86400;

        $timeElapsed = time() - strtotime($resetRow->created_at);

        if ($timeElapsed > $tokenExpiresIn) {

            $resetRow->delete();

            return redirect('/login')
                ->with('token_expired', 'Your reset password link expired');
        }


        $user = User::select('id', 'password')
            ->where('email', '=', $resetRow->email)
            ->first();

        if (Hash::check($password, $user->password)) {

            return redirect()
                ->back()
                ->with(
                    'invalid',
                    'Password cannot be the same as your old password
                    '
                );
        }

        $user->password = Hash::make($password);

        $user->save();

        $resetRow->delete();

        return redirect('/login')
            ->with('password_changed', 'Your password was reset. You can now login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login.login');
    }

    public function createLogin(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $userData = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (Auth::attempt($userData)) {

            $loggedInUserID = $this->getLoggedInUser($request->input('email'));

            $userInitials = $this->getUserInitials($loggedInUserID);

            Session::put('userInitials', $userInitials);

            Session::put('userID', $loggedInUserID);

            return redirect('/')->with(['welcome' => "Welcome back, " . Auth::user()->username]);
        } else {
            return back()->withErrors(['matchError' => 'These credentials do not match']);
        }
    }

    public function createLogout()
    {
        Auth::logout();
        Session::flush();

        return redirect('/login');
    }

    private function getLoggedInUser($email)
    {
        $userID = User::where('email', $email)->first();

        return $userID->id;
    }

    private function getUserInitials($userID)
    {
        $user = User::where('id', $userID)->first();

        $fullName = explode(' ', $user->name);

        $initials = array_map(function ($name) {

            return strtoupper(substr($name, 0, 1));
        }, $fullName);

        return implode('', $initials);
    }
}

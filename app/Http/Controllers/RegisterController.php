<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

class RegisterController extends Controller
{
    public function showRegister()
    {
        //GET
        return view('register.register');
    }

    public function createRegister(Request $request)
    {
        $validatedData = $request->validate([
            'fullname' => [
                'required',
                'min:6',
                'max:35',
                'regex:/^[a-zA-Z0-9\s]+$/'
            ],
            'username' => [
                'required',
                'unique:App\Models\User,username',
                'min:4',
                'max:20',
                'alpha_dash'
            ],
            'email' => [
                'required',
                'email',
                'unique:App\Models\User,email'
            ],
            'password' => [
                'required',
                'min:6',
                'max:25'
            ],
            'file' => [
                'max:2000',
            ],
        ]);

        $user = new User;

        $file = $request->file('file');

        if ($file !== null) {
            $imageFilePath = $this->uploadFileToS3($file);

            $user->image = $imageFilePath;
        } else {
            $user->image = '';
        }


        $user->email = $request->input('email');
        $user->name = ucwords($request->input('fullname'));
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));


        $user->save();

        return redirect('/login');
    }

    private function uploadFileToS3($file)
    {
        $filePath = time() . '_' . $file->getClientOriginalName();

        Storage::disk('s3')->put($filePath, fopen($file, 'r+'), 'public');

        return $filePath;
    }
}

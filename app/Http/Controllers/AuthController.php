<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(){
        return view ('register');
    }

    public function registerStore(Request $request){
        request()->validate(
            [
                'name' => 'required|min:4|unique:users',
                'email' => 'required|email:rfc|unique:users',
                'password' => 'required|min:4|unique:users',
            ],
            [
                'name.required' => 'Nama harus diisi !',
                'name.unique' => 'Nama telah digunakan ;!',
                'name.min' => 'Nama minimal harus 4 karakter !!',
                'email.required' => 'Email harus diisi',
                'email.unique' => 'Email telah digunakan ;!',
                'password.unique' => 'Password telah digunakan ;!',
                'password.required' => 'Password harus diisi !',
                'password.min' => 'Password minimal harus 4 karakter !!',
            ]
        );
        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => 'user',
            'password' => bcrypt($request->password),
            'remember_token' => Str::random(60)

        ]);

        return view('/login')->with('alert', 'Registrasi Berhasil!');
    }
    public function login(){
        return view('login');
    }

    public function loginStore(Request $request){
        $data = $request->validate(
            [
                'email' => 'required|email:rfc',
                'password' => 'required|min:4',
            ],
            [
                'email.required' => 'Email harus diisi !',
                'password.required' => 'Password harus diisi !',
                'password.min' => 'Password minimal harus 4 karakter !!',
            ]
        );
        if (Auth::attempt($data)) {
            $user = Auth::user();

             if ($user->role_id == "admin") {
                 return redirect('/hotel');
             }
             else if ($user->role_id == "user"){
                 return redirect('/tb');
             }
         }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('alert', 'Anda telah logout');
    }
}

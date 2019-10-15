<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Panggil Model User
use App\User;
// Panggil Model Auth
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Melihat daftar User
    public function index(){
        $users = User::all();
        foreach($users as $user){
            $user->view_user = [
                'href' => 'api/v1/user' . $user->id,
                'method' => 'get'
            ];
        }
        // Buat Respon
        $response = [
            'msg' => 'List user yang terdaftar : ',
            'user' => $users
        ];
        // Berikan respon
        return response()->json($response, 200);
    }
    // Fungsi Register
    public function store(Request $request){
        // Validasi
        $rule = [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5'
        ];
        $this->validate($request, $rule);

        // Variabel menampung hasil Inputan
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        // Membuat objek user baru
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
        // Jika register berhasil
        if($user->save()){
            $user->signin = [
                'href' => 'api/v1/user/signin',
                'method' => 'POST',
                'params' => 'email, password'
            ];
            // Buat pesan jika berhasil
            $message = [
                'msg' => 'User berhasil dibuat!',
                'user' => $user
            ];
            // Berikan pesan jika berhasil
            return response()->json($message, 201);
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'User gagal dibuat!'
        ];
        // Berikan pesan jika gagal
        return response()->json($response, 404);
    }
    
    // Fungsi Login
    public function signin(Request $request){
        // Menampung hasil inputan
        $email = $request->input('email');
        $password = $request->input('password');
        // Mencoba Login
        $login = [
            'email' => $email,
            'password' => $password
        ];
        if(Auth::attempt($login)){
            $user = Auth::user();
            // Buat pesan jika berhasil login
            $message = [
                'msg' => 'Signin Berhasil!',
            ];
            // Berikan pesan jika berhasil login
            return response()->json($message, 200);
        }
        // Buat pesan jika gagal
        $response = [
            'msg' => 'Login berhasil',
        ];
        // Berikan pesan jika berhasil login
        return response()->json($response, 404);
    }
}

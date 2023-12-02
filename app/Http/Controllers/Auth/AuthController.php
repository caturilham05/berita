<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.login', ['title' => 'Admin Login']);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required'],
            'password' => ['required'],
        ]);

        $user = User::select('id', 'password', 'is_admin')->where('email', addslashes($credentials['email']))->first();
        if (empty($user)) return back()->with('loginError', 'Password atau Email yang anda masukkan salah, silahkan coba kembali!');
        if (!Hash::check($credentials['password'], $user['password'])) return back()->with('loginError', 'Password atau Email yang anda masukkan salah, silahkan coba kembali!');

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin');
    }
}

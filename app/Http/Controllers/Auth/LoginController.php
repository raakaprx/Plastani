<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect setelah login berdasarkan role
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, Admin!');
        }

        return redirect()->route('home')
            ->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
    }

    /**
     * Redirect setelah logout
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request):RedirectResponse{

        $credential = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'type' => 'patient'
        ]);
        if(Auth::check()){
            return redirect()->route('home');
        }
        if (Auth::attempt($credential)) {
            $request->session()->regenerate();
            return redirect()->back()
                ->with('show_success_modal', true)
                ->with('success_message', 'Login feito com sucesso!')
                ->with('success_redirect', route('home'));
        } else {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

    }
    public function logout(Request $request):RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()
            ->with('show_success_modal', true)
            ->with('success_message', 'Logout feito com sucesso!')
            ->with('success_redirect', route('home'));
    }
}

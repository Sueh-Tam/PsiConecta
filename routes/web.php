<?php

use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::prefix('auth')->group(function () {
    Route::prefix('patient')->group(function () {
        Route::get('/signup', function () {
            return view('Users.Patients.register');
        })->name('user.signup');
        Route::post('/register',[PatientController::class, 'store'])->name('patient.register');
        Route::get('/login', function () {
            return view('Users.Patients.login');
        })->name('user.login');
        Route::post('/login',[PatientController::class, 'login'])->name('patient.login');
        Route::get('/logout',[PatientController::class, 'logout'])->name('patient.logout');
        Route::get('/dashboard', function () {
            if(Auth::check()){
                return view('Dashboard.Consults.index');
            }else{
                return redirect()->route('home')->withErrors([
                    'mensagem' => 'Acesso não autorizado'

                ]);
            }
        })->name('patient.dashboard');
    });
});

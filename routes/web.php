<?php

use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::prefix('auth')->group(function () {
    Route::get('/signup', function () {
        return view('Users.Pacients.register');
    })->name('user.login');
    Route::post('/register',[PatientController::class, 'store'])->name('patient.register');
});

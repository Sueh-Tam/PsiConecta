<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Clinic\ClinicController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Psychologist\PsychologistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('home');

//
// ROTAS DE AUTENTICAÇÃO
//
Route::prefix('auth')->group(function () {

    // --- Paciente ---
    Route::prefix('patient')->group(function () {
        Route::get('/signup', fn () => view('Users.Patients.register'))->name('user.signup');
        Route::post('/register', [PatientController::class, 'store'])->name('patient.register');

        Route::get('/login', fn () => view('Users.Patients.login'))->name('user.login');
        Route::post('/login', [PatientController::class, 'login'])->name('patient.login');

        Route::get('/logout', [PatientController::class, 'logout'])->name('patient.logout');
    });

    // --- Clínica ---
    Route::prefix('clinic')->group(function () {
        Route::get('/signup', fn () => view('Users.Clinics.register'))->name('clinic.signup');
        Route::post('/register', [ClinicController::class, 'store'])->name('clinic.register');

        Route::get('/login', fn () => view('Users.Clinics.login'))->name('clinic.login');
        Route::post('/login', [ClinicController::class, 'login'])->name('clinic.login');

        Route::get('/logout', [ClinicController::class, 'logout'])->name('clinic.logout');
    });

    // --- Admin ---
    Route::prefix('admin')->group(function () {
        Route::get('/login', fn () => view('Users.admin.login'))->name('admin.login.view');
        Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

//
// ROTAS DE PACIENTE
//
Route::prefix('patient')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('Dashboard.Consults.index'))->name('patient.dashboard');

    // Perfil
    Route::get('/profile', function () {
        if (Auth::check()) {
            return view('Users.Patients.profile');
        }
        return redirect()->route('home')->withErrors(['mensagem' => 'Acesso não autorizado']);
    })->name('patient.profile');

    Route::put('/profile/update', [PatientController::class, 'update'])->name('patient.profile.update');
    Route::delete('/profile/delete', [PatientController::class, 'delete'])->name('patient.profile.delete');
});

//
// ROTAS DE ADMIN
//

Route::prefix('admin')->middleware('auth')->group(function () {

    // Dashboard de clínicas
    Route::get('/dashboard', [ClinicController::class, 'clinics'])->name('admin.dashboard');

    // Atualização de dados da clínica
    Route::put('/dashboard/update', [ClinicController::class, 'updateClinic'])->name('admin.update');
});

//
// ROTAS DE CLÍNICA
//
Route::prefix('clinic')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => view('Dashboard.clinic.index'))->name('clinic.dashboard');

    Route::prefix('psychologist')->group(function () {
        Route::get('/index', [PsychologistController::class,'psychologistByClinic'])->name('clinic.psychologist.index');
        Route::post('/store', [PsychologistController::class, 'store'])->name('clinic.psychologist.store');
        Route::put('/update', [PsychologistController::class, 'update'])->name('clinic.psychologist.update');
    });

});

<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Attendant\AttendantController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\Clinic\ClinicController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Psychologist\PsychologistController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Models\Availability;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Página inicial
Route::get('/', function () {
    $clinics = User::where('type', 'clinic')
    ->where('situation','valid')
    ->where('status','active')->get();
    return view('welcome')->with('clinics', $clinics);
})->name('home');

Route::get('/clinic/{id}/psychologists', function ($id) {
    $clinic = User::find($id);
    $clinics = User::where('type', 'clinic')
    ->where('situation','valid')->get();
    $psychologists = $clinic->psychologists()->get();
    
    return view('clinic.psychologists')->with(['psychologists' => $psychologists,
    'clinics' => $clinics,'clinic' => $clinic]);
})->name('psychologist.clinic');

Route::get('/api/clinic/{id}/psychologists', function ($id) {
    $clinic = User::find($id);
    $psychologists = $clinic->psychologists()->get();
    
    return $psychologists;
});
//
// ROTAS DE AUTENTICAÇÃO
//
Route::prefix('auth')->group(function () {

    Route::get('/login', function () {
        return view('Users.login');
    })->name('login');
    Route::post('/login',[UserController::class, 'login'])->name('login');
    Route::get('/logout',[UserController::class, 'logout'])->name('auth.logout');
    Route::get('/reset/password', function () {
        return view('Users.Patients.reset_password');
    })->name('auth.reset.password');
    Route::post('/reset/password',[UserController::class, 'resetPassword'])->name('auth.reset.password');
    // --- Paciente ---
    Route::prefix('patient')->group(function () {
        Route::get('/signup', fn () => view('Users.Patients.register'))->name('user.signup');
        Route::post('/register', [PatientController::class, 'store'])->name('patient.register');
    });

    // --- Clínica ---
    Route::prefix('clinic')->group(function () {
        Route::get('/signup', fn () => view('Users.Clinics.register'))->name('clinic.signup');
        Route::post('/register', [ClinicController::class, 'store'])->name('clinic.register');

    });
});

//
// ROTAS DE PACIENTE
//
Route::prefix('patient')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [PatientController::class,'dashboard'])->name('patient.dashboard');
    Route::put('/cancel/{appointment}', [AppointmentController::class,'cancellByPatient'])->name('patient.cancellByPatient.appointment');
    Route::get('/packages', [PackageController::class,'packagesByPatient'])->name('patient.packages');
    // API para buscar psicólogo do paciente
    Route::get('/api/patients/{id}/psychologist', function ($id) {
        $patient = \App\Models\User::find($id);
        $activePackage = $patient->activePackage();

        if ($activePackage) {
            $psychologist = \App\Models\User::find($activePackage->psychologist_id);

            // Buscar disponibilidades do psicólogo
            $availabilities = \App\Models\Availability::where('id_psychologist', $psychologist->id)
                ->where('status', 'available')
                ->whereDate('dt_Availability', '>=', now())
                ->get();

            // Organizar disponibilidades por dia da semana
            $availableDays = [];
            $availableTimesByDay = [];

            foreach ($availabilities as $availability) {
                $dayOfWeek = Carbon::parse($availability->dt_Availability)->format('d/m/Y');
                if (!in_array($dayOfWeek, $availableDays)) {
                    $availableDays[] = $dayOfWeek;
                }

                if (!isset($availableTimesByDay[$dayOfWeek])) {
                    $availableTimesByDay[$dayOfWeek] = [];
                }

                $availableTimesByDay[$dayOfWeek][] = $availability->hr_Availability;
            }

            return response()->json([
                'psychologist' => $psychologist,
                'available_days' => $availableDays,
                'available_times' => $availableTimesByDay
            ]);
        }

        return response()->json(['psychologist' => null, 'available_days' => [], 'available_times' => []]);
    });
    Route::get('/api/psychologist/{id}', function ($id) {
        $patient = \App\Models\User::find(Auth::user()->id);
        $psychologist  = \App\Models\User::find($id);;

        $activePackage = $patient->activePackage()
            ->where('psychologist_id', $psychologist->id)
            ->where('balance','!=','0')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activePackage) {

            // Buscar disponibilidades do psicólogo
            $availabilities = \App\Models\Availability::where('id_psychologist', $psychologist->id)
                ->where('status', 'available')
                ->whereDate('dt_Availability', '>=', now())
                ->get();

            // Organizar disponibilidades por dia da semana
            $availableDays = [];
            $availableTimesByDay = [];

            foreach ($availabilities as $availability) {
                $dayOfWeek = Carbon::parse($availability->dt_Availability)->format('d/m/Y');
                if (!in_array($dayOfWeek, $availableDays)) {
                    $availableDays[] = $dayOfWeek;
                }

                if (!isset($availableTimesByDay[$dayOfWeek])) {
                    $availableTimesByDay[$dayOfWeek] = [];
                }

                $availableTimesByDay[$dayOfWeek][] = $availability->hr_Availability;
            }

            return response()->json([
                'psychologist' => $psychologist,
                'available_days' => $availableDays,
                'available_times' => $availableTimesByDay,
                'package' => $activePackage,
            ]);
        }

        return response()->json(['psychologist' => null, 'available_days' => [], 'available_times' => []]);
    });

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


    Route::get('/dashboard', [ClinicController::class, 'clinics'])->name('admin.dashboard');
    Route::put('/dashboard/update', [ClinicController::class, 'updateClinic'])->name('admin.update');
});

//
// ROTAS DE CLÍNICA
//
Route::prefix('clinic')->middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ClinicController::class, 'dashboard'])->name('clinic.dashboard');
    Route::prefix('appointments')->group(function () {
        Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::patch('/{appointment}/cancel', [AppointmentController::class, 'edit'])->name('appointments.cancel');
        Route::patch('/{appointment}/canceledEarly', [AppointmentController::class, 'canceledEarly'])->name('appointments.canceled.early');
        Route::patch('/{appointment}/complet', [AppointmentController::class, 'completAppointment'])->name('appointments.complet.appointment');
        Route::get('/{appointment}/show', [AppointmentController::class, 'show'])->name('appointments.edit');
        Route::put('/{appointment}/update', [AppointmentController::class,'finishAppointment'])->name('appointments.update');
    });
    
    Route::prefix('psychologist')->group(function () {
        Route::get('/index', [PsychologistController::class,'psychologistByClinic'])->name('clinic.psychologist.index');
        Route::post('/store', [PsychologistController::class, 'store'])->name('clinic.psychologist.store');
        Route::put('/update', [PsychologistController::class, 'update'])->name('clinic.psychologist.update');
    });
    Route::prefix('Attendant')->group(function () {
        Route::get('/index', [AttendantController::class,'attendantByClinic'])->name('clinic.attendant.index');
        Route::post('/store', [AttendantController::class, 'store'])->name('clinic.attendant.store');
        Route::put('/update/{id}', [AttendantController::class, 'update'])->name('clinic.attendant.update');
    });
    Route::prefix('patient')->group(function () {
        Route::get('/index', [PatientController::class,'patientByClinic'])->name('clinic.patient.index');
        Route::post('/store', [PatientController::class, 'store'])->name('clinic.patient.store');
        Route::put('/update/{id}', [PatientController::class, 'update'])->name('clinic.patient.update');
    });
    Route::prefix('packages')->group(function () {
        Route::get('/index', [PackageController::class,'packagesByClinic'])->name('clinic.packages.index');
        Route::post('/store', [PackageController::class, 'store'])->name('clinic.packages.store');
        Route::put('/update/{id}', [PackageController::class, 'update'])->name('clinic.packages.update');
    });
});

Route::prefix('psychologist')->middleware('auth')->group(function () {
    Route::get('/dashboard', [PsychologistController::class,'consultsByPsychologist'])->name('psychologist.dashboard');
    Route::get('profile',function(){
        return view('Dashboard.Psychologists.profile');
    })->name('psychologist.profile');
    Route::get('disponibility', [AvailabilityController::class, 'show'])->name('psychologist.disponibility');
    Route::post('disponibility/store', [AvailabilityController::class, 'store'])->name('psychologist.disponibility.store');
    Route::delete('disponibility/delete/{id}', [AvailabilityController::class, 'destroy'])->name('psychologist.disponibility.delete');
    Route::post('/psychologist/availability/deactivate', [AvailabilityController::class, 'deactivate'])->name('psychologist.availability.deactivate');
    Route::post('/availability/restore', [AvailabilityController::class, 'restore'])->name('psychologist.availability.restore');
    Route::get('/patients', [PsychologistController::class,'psychologistPatients'])->name('psychologist.patients');
    Route::get('patient/details/{id}',[PsychologistController::class,'patientDetails'])->name('psychologist.patient.details');
});

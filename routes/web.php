<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('landing.master');
// });
Route::get('/', function () {
    // dd(Auth::user()->role);
    return view('user.profile');
});
Route::get('/', function () {
    return view('landing.master');
});

Auth::routes();
// Route User
Route::middleware(['auth', 'user-role:user'])->group(function () {
    Route::get("/home", [PatientController::class, 'userindex'])->name("userindex");
    Route::get("/appt-status-list", [PatientController::class, 'statuslist'])->name("statuslist");
    Route::get("/appt-history-list", [PatientController::class, 'historylist'])->name("historylist");
    Route::get("/booking-apt", [PatientController::class, 'bookingpage'])->name("booking-form");
    Route::post('/get-doctor', [PatientController::class, 'get_doctor'])->name("appointment.getDoctor");
    Route::post('/book-appointment', [PatientController::class, 'store'])->name("appointment.booking");

    //profle
    Route::get("/profile", [PatientController::class, 'profile'])->name("profile");
    Route::get("/profile-setting", [PatientController::class, 'usersetting'])->name("usersetting");
    Route::put('/profile-setting/{id}', [PatientController::class, 'update'])->name('userprofile.update');

});

// Route Doctor
Route::middleware(['auth', 'user-role:doctor'])->group(function () {
    Route::get("/doctor/home", [DoctorController::class, 'doctorHome'])->name("doctor.home");
    Route::get("/doctor/newappt", [DoctorController::class, 'newapptdoc'])->name("newapptdoc");
    Route::get('/detail-appointment/{id}/{aptnum}', [DoctorController::class, 'show'])->name('detailAppointment.show');
    Route::put('/appointment/{id}', [DoctorController::class, 'update'])->name('appointment.update');

});
// Route Admin
Route::middleware(['auth', 'user-role:admin'])->group(function () {
    Route::get("/admin/home", [AdminController::class, 'adminHome'])->name("admin.home");
});

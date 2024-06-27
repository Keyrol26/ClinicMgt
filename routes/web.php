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
    //new appt page
    Route::get("/doctor/newappt", [DoctorController::class, 'newapptdoc'])->name("newapptdoc");
    Route::get("/doctor/newappt/search", [DoctorController::class, 'searchnewapptdoc'])->name("searchnewapptdoc");

    //approved appt page
    Route::get("/doctor/approvedappt", [DoctorController::class, 'approvedapptdoc'])->name("approvedapptdoc");
    Route::get("/doctor/approvedappt/search", [DoctorController::class, 'searchapprovedapptdoc'])->name("searchapprovedapptdoc");

    //Cancel appt page
    Route::get("/doctor/cancelappt", [DoctorController::class, 'cancelapptdoc'])->name("cancelapptdoc");
    Route::get("/doctor/cancelappt/search", [DoctorController::class, 'searchcancelapptdoc'])->name("searchcancelapptdoc");

    //All appt page
    Route::get("/doctor/allappt", [DoctorController::class, 'allapptdoc'])->name("allapptdoc");
    Route::get("/doctor/allappt/search", [DoctorController::class, 'searchallapptdoc'])->name("searchallapptdoc");

    //All appt page
    Route::get("/doctor/patientlist", [DoctorController::class, 'patientlistdoc'])->name("patientlistdoc");
    Route::get("/doctor/patientlist/search", [DoctorController::class, 'searchpatientlistdoc'])->name("searchpatientlistdoc");

    //Appt Details
    Route::get('/detail-appointment/{id}/{aptnum}', [DoctorController::class, 'show'])->name('detailAppointment.show');
    Route::put('/appointment/{id}', [DoctorController::class, 'update'])->name('appointment.update');
    Route::put('/appointment-report/{id}', [DoctorController::class, 'reportupdate'])->name('appointmentreport.update');

    //Profile
    Route::get("/doctor/profile", [DoctorController::class, 'profile'])->name("docprofile");
    Route::get("/doctor/profile-setting", [DoctorController::class, 'docsetting'])->name("docsetting");
    Route::put('/doctor/profile-setting/{id}', [DoctorController::class, 'docupdate'])->name('docprofile.update');
    Route::get("/doctor/profile-security", [DoctorController::class, 'docpass'])->name("docpass");
    Route::put('/doctor/profile-security/{id}', [DoctorController::class, 'docpassupdate'])->name('docpass.update');

    
});
// Route Admin
Route::middleware(['auth', 'user-role:admin'])->group(function () {
    Route::get("/admin/home", [AdminController::class, 'adminHome'])->name("admin.home");
});

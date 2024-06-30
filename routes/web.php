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

    //status page
    Route::get("/appt-status-list", [PatientController::class, 'statuslist'])->name("statuslist");
    Route::get("/appt-status-list/search", [PatientController::class, 'searchstatuslist'])->name("statuslist.search");

    //history page
    Route::get("/appt-history-list", [PatientController::class, 'historylist'])->name("historylist");
    Route::get("/appt-history-list/search", [PatientController::class, 'searchhistorylist'])->name("historylist.search");
    
    //Rescheduled page
    Route::get("/appt-rescheduled-list", [PatientController::class, 'rescheduledlist'])->name("rescheduledlist");
    Route::get("/appt-rescheduled-list/search", [PatientController::class, 'searchrescheduledlist'])->name("rescheduledlist.search");

    //booking form page
    Route::get("/booking-apt", [PatientController::class, 'bookingpage'])->name("booking-form");
    Route::post('/get-doctor', [PatientController::class, 'get_doctor'])->name("appointment.getDoctor");
    Route::post('/book-appointment', [PatientController::class, 'store'])->name("appointment.booking");

    //Appt Details
    Route::get('/detail-appointment/{id}/{aptnum}', [PatientController::class, 'appointmentshow'])->name('userdetailAppointment.show');
    Route::put('/appointment/{id}', [PatientController::class, 'appointmentupdate'])->name('userappointment.update');

    //profile
    Route::get("/profile", [PatientController::class, 'profile'])->name("profile");
    Route::get("/profile-setting", [PatientController::class, 'usersetting'])->name("usersetting");
    Route::put('/profile-setting/{id}', [PatientController::class, 'update'])->name('userprofile.update');
    Route::get("/profile-security", [PatientController::class, 'usersecurity'])->name("usersecurity");
    Route::put('/profile-security/password/{id}', [PatientController::class, 'userpassupdate'])->name('userpassupdate.update');
    Route::put('/profile-security/email/{id}', [PatientController::class, 'useremailupdate'])->name('useremailupdate.update');

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

    //Rescheduled appt page
    Route::get("/doctor/rescheduledappt", [DoctorController::class, 'rescheduledapptdoc'])->name("rescheduledapptdoc");
    Route::get("/doctor/rescheduledappt/search", [DoctorController::class, 'searchrescheduledapptdoc'])->name("searchrescheduledapptdoc");

    //All appt page
    Route::get("/doctor/allappt", [DoctorController::class, 'allapptdoc'])->name("allapptdoc");
    Route::get("/doctor/allappt/search", [DoctorController::class, 'searchallapptdoc'])->name("searchallapptdoc");

    //All Patient page
    Route::get("/doctor/patientlist", [DoctorController::class, 'patientlistdoc'])->name("patientlistdoc");
    Route::get("/doctor/patientlist/search", [DoctorController::class, 'searchpatientlistdoc'])->name("searchpatientlistdoc");

    //Appt Details
    Route::get('/doctor/detail-appointment/{id}/{aptnum}', [DoctorController::class, 'show'])->name('detailAppointment.show');
    Route::put('/doctor/appointment/{id}', [DoctorController::class, 'update'])->name('appointment.update');
    Route::put('/doctor/appointment-report/{id}', [DoctorController::class, 'reportupdate'])->name('appointmentreport.update');

    //Profile
    Route::get("/doctor/profile", [DoctorController::class, 'profile'])->name("docprofile");
    Route::get("/doctor/profile-setting", [DoctorController::class, 'docsetting'])->name("docsetting");
    Route::put('/doctor/profile-setting/{id}', [DoctorController::class, 'docupdate'])->name('docprofile.update');
    Route::get("/doctor/profile-security", [DoctorController::class, 'docpass'])->name("docpass");
    Route::put('/doctor/profile-security/password/{id}', [DoctorController::class, 'docpassupdate'])->name('docpass.update');
    Route::put('/doctor/profile-security/email/{id}', [DoctorController::class, 'docpassemail'])->name('docemail.update');

    
});
// Route Admin
Route::middleware(['auth', 'user-role:admin'])->group(function () {
    Route::get("/admin/home", [AdminController::class, 'index'])->name("admin.index");

    //new appt page
    Route::get("/admin/newappt", [AdminController::class, 'newapptdoc'])->name("admin.newapptdoc");
    Route::get("/admin/newappt/search", [AdminController::class, 'searchnewapptdoc'])->name("admin.searchnewapptdoc");

    //approved appt page
    Route::get("/admin/approvedappt", [AdminController::class, 'approvedapptdoc'])->name("admin.approvedapptdoc");
    Route::get("/admin/approvedappt/search", [AdminController::class, 'searchapprovedapptdoc'])->name("admin.searchapprovedapptdoc");

    //Cancel appt page
    Route::get("/admin/cancelappt", [AdminController::class, 'cancelapptdoc'])->name("admin.cancelapptdoc");
    Route::get("/admin/cancelappt/search", [AdminController::class, 'searchcancelapptdoc'])->name("admin.searchcancelapptdoc");

    //Rescheduled appt page
    Route::get("/admin/rescheduledappt", [AdminController::class, 'resdapptdoc'])->name("admin.resdapptdoc");
    Route::get("/admin/rescheduledappt/search", [AdminController::class, 'searchresdapptdoc'])->name("admin.searchresdapptdoc");
    
    //All appt page
    Route::get("/admin/allappt", [AdminController::class, 'allapptdoc'])->name("admin.allapptdoc");
    Route::get("/admin/allappt/search", [AdminController::class, 'searchallapptdoc'])->name("admin.searchallapptdoc");

    //All patient page
    Route::get("/admin/patientlist", [AdminController::class, 'patientlistdoc'])->name("admin.patientlistdoc");
    Route::get("/admin/patientlist/search", [AdminController::class, 'searchpatientlistdoc'])->name("admin.searchpatientlistdoc");

    //All Doctor page
    Route::get("/admin/doctorlist", [AdminController::class, 'doctorlist'])->name("admin.doclistdoc");
    Route::get("/admin/doctorlist/search", [AdminController::class, 'searchdoctorlist'])->name("admin.searchdoctorlist");

    //Appt Details
    Route::get('/admin/detail-appointment/{id}/{aptnum}', [AdminController::class, 'show'])->name('admindetailAppointment.show');
    Route::put('/admin/appointment/{id}', [AdminController::class, 'update'])->name('adminappointment.update');
    Route::put('/admin/appointment-report/{id}', [AdminController::class, 'reportupdate'])->name('adminappointmentreport.update');

    //Admin Profile
    Route::get("/admin/profile", [AdminController::class, 'profile'])->name("adminprofile");
    Route::get("/admin/profile-setting", [AdminController::class, 'adminsetting'])->name("adminsetting");
    Route::put('/admin/profile-setting/{id}', [AdminController::class, 'adminupdate'])->name('adminprofile.update');
    Route::get("/admin/profile-security", [AdminController::class, 'adminpass'])->name("adminpass");
    Route::put('/admin/profile-security/password/{id}', [AdminController::class, 'adminpassupdate'])->name('adminpass.update');

    //Doc Profile
    Route::get("/admin/doctor/profile/{id}", [AdminController::class, 'docprofile'])->name("admindocprofile");
    Route::get("/admin/doctor/{id}/profile-setting", [AdminController::class, 'admindocsetting'])->name("admindocsetting");
    Route::put('/admin/doctor/profile-setting/{id}', [AdminController::class, 'admindocupdate'])->name('admindocprofile.update');
    Route::get("/admin/doctor/{id}/profile-security", [AdminController::class, 'admindocpass'])->name("admindocpass");
    Route::put('/admin/doctor/profile-security/password/{id}', [AdminController::class, 'admindocpassupdate'])->name('admindocpass.update');
    Route::put('/admin/doctor/profile-security/email/{id}', [AdminController::class, 'admindocemail'])->name('admindocemail.update');

    Route::get("/admin/doctorlist/new-doctor", [AdminController::class, 'newdoctor'])->name("newdoctor");
    Route::post('/admin/doctorlist/new-doctor/register', [AdminController::class, 'storedoctor'])->name("adddoctor");

    //Patient Profile
    Route::get("/admin/patient/profile/{id}", [AdminController::class, 'patientprofile'])->name("patientprofile");
    Route::get("/admin/patient/{id}/profile-setting", [AdminController::class, 'patientsetting'])->name("patientsetting");
    Route::put('/admin/patient/profile-setting/{id}', [AdminController::class, 'patientupdate'])->name('patientupdate.update');
    Route::get("/admin/patient/{id}/profile-security", [AdminController::class, 'patientpass'])->name("patientpass");
    Route::put('/admin/patient/profile-security/password/{id}', [AdminController::class, 'patientpassupdate'])->name('patientpassupdate.update');
    Route::put('/admin/patient/profile-security/email/{id}', [AdminController::class, 'patientemailupdate'])->name('patientemailupdate.update');

    Route::delete('/admin/appointment/delete/{id}', [AdminController::class, 'apptdelete'])->name('apptdelete.delete');
    Route::delete('/admin/doctor/delete/{id}', [AdminController::class, 'doctordelete'])->name('doctordelete.delete');
    Route::delete('/admin/patient/delete/{id}', [AdminController::class, 'patientdelete'])->name('patientdelete.delete');
});


<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PakaianAdatController;
use App\Http\Controllers\ClientReservationController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\clientCarController;
use App\Models\PakaianAdat;
use App\Models\User;
use App\Models\Reservation;


// ------------------- guest routes --------------------------------------- //
// Menggunakan HomeController untuk menampilkan halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pakaian-adat', [PakaianAdatController::class, 'index'])->name('pakaianAdat');
Route::get('/pakaianAdat/search', [SearchController::class, 'search'])->name('search');

Route::get('location', function () {
    return view('location');
})->name('location');

Route::get('contact_us', function () {
    return view('contact_us');
})->name('contact_us');

Route::get('admin/login', [Admin\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [Admin\LoginController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('/privacy_policy',
function () {
    return view('Privacy_Policy');
})->name('privacy_policy');

Route::get('/terms_conditions',
function () {
    return view('Terms_Conditions');
})->name('terms_conditions');


// -------------------------------------------------------------------------//




// ------------------- admin routes --------------------------------------- //

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', Admin\DashboardController::class)->name('dashboard');

    Route::resource('pakaian-adat', Admin\PakaianAdatController::class)->except(['show'])->parameters(['pakaian-adat' => 'pakaianAdat']);
    Route::resource('reservations', Admin\ReservationController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('users', Admin\UsersController::class)->only(['index', 'show']);

    Route::get('/updatePayment/{reservation}', [Admin\ReservationController::class, 'editPayment'])->name('editPayment');
    Route::put('/updatePayment/{reservation}', [Admin\ReservationController::class, 'updatePayment'])->name('updatePayment');

    Route::get('/updateReservation/{reservation}', [Admin\ReservationController::class, 'editStatus'])->name('editStatus');
    Route::put('/updateReservation/{reservation}', [Admin\ReservationController::class, 'updateStatus'])->name('updateStatus');
    Route::get('/settings', [Admin\SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
});

// --------------------------------------------------------------------------//




// ------------------- client routes --------------------------------------- //

Route::get('/reservations/{pakaianAdat}', [ClientReservationController::class, 'create'])->name('pakaian-adat.reservation');
Route::post('/reservations/{pakaianAdat}', [ClientReservationController::class, 'store'])->name('pakaian-adat.reservationStore');

route::get('invoice/{reservation}', [invoiceController::class, 'invoice'])->name('invoice');


//---------------------------------------------------------------------------//

// Auth::routes(); // Menonaktifkan rute login/register untuk user biasa

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin']);
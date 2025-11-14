<?php

use App\Http\Controllers\MidtransCallbackController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PakaianAdatController;
use App\Http\Controllers\ClientReservationController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SearchController;


// ------------------- user routes --------------------------------------- //
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pakaian-adat', [PakaianAdatController::class, 'index'])->name('pakaianAdat');
Route::get('/pakaianAdat/search', [SearchController::class, 'search'])->name('search');
Route::get('/reservations/{pakaianAdat}', [ClientReservationController::class, 'create'])->name('pakaian-adat.reservation');
Route::post('/reservations/{pakaianAdat}', [ClientReservationController::class, 'store'])->name('pakaian-adat.reservationStore');
Route::get('invoice/{reservation}', [InvoiceController::class, 'show'])->name('invoice');
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

Route::get('/thankyou/{reservation}', function ($reservation_id) {
    $reservation = \App\Models\Reservation::findOrFail($reservation_id);
    return view('thankyou', compact('reservation'));
})->name('thankyou');

// ------------------- chatbot route -------------------------------------- //
Route::post('/chatbot', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
Route::get('/chatbot/cek-status/{transaction_id}', [ChatbotController::class, 'cekStatus']);

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

// Rute untuk pembayaran
Route::get('/payment/{reservation}', [ClientReservationController::class, 'payment'])->name('payment');
Route::get('/payment/finish/{reservation}', [ClientReservationController::class, 'paymentFinish'])->name('payment.finish');
Route::post('/midtrans/callback', [MidtransCallbackController::class, 'handle'])->name('midtrans.callback');
Route::post('/payment/cod/{reservation}', [ClientReservationController::class, 'payAtStore'])->name('payment.cod');

// Auth::routes(); // Menonaktifkan rute login/register untuk user biasa

Route::get('/admin', function() {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'admin']);
<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\OrderController;
use App\Http\Controllers\web\PaymentController;
use App\Http\Controllers\web\RouteController;
use App\Http\Controllers\web\SandboxController;
use App\Http\Controllers\web\SchedulesController;
use App\Http\Controllers\web\SearchController;
use App\Http\Controllers\web\TicketController;
use App\Http\Controllers\web\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/sandbox', [SandboxController::class, 'index'])->name('sandbox.index');
Route::get('/routes', [RouteController::class, 'index'])->name('routes.index');
Route::get('/schedules', [SchedulesController::class, 'index'])->name('schedules.index');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'doLogin']);
Route::post('/register', [AuthController::class, 'doRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');

Route::get('/email/verify', function () {
    return view('auth.verify-email',[
        'user' => auth()->user(),
    ]);
})->middleware('auth')->name('verification.notice');
Route::post('/email/resend', [AuthController::class, 'resend'])->name('verification.resend');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->intended()->with('success', 'Почта успешно подтверждена!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/book-tickets/', [OrderController::class, 'bookTickets'])->name('book.tickets')->middleware('auth');
Route::post('/orders/book-tickets/', [OrderController::class, 'prepareBookTicket'])->name('book.tickets.post');
Route::post('/orders/buy-tickets', [OrderController::class, 'buyTickets'])->name('orders.confirm');
Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');

Route::match(['GET', 'POST'],'/payments/callback', [PaymentController::class, 'callback'])->name('payment.callback');

Route::get('/tickets/{id}/pdf', [TicketController::class, 'generatePdf'])->name('tickets.pdf');
Route::get('/ticket/refund/{id}', [TicketController::class, 'refundView'])->name('ticket.refund.view');
Route::post('/ticket/refund', [TicketController::class, 'refund'])->name('ticket.refund');

Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

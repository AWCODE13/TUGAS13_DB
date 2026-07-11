<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\TransactionController;




Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show');

Route::get('/checkout/{event}', [CheckoutController::class, 'create'])
    ->name('checkout.create');

Route::post('/checkout/{event}', [CheckoutController::class, 'store'])
    ->name('checkout.store');

    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])
    ->name('checkout.payment');

    Route::get('/success/{order_id}',
    [\App\Http\Controllers\CheckoutController::class, 'success'])
    ->name('checkout.success');

Route::get('/my-ticket', [TicketController::class, 'index'])
    ->name('ticket');
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::post('/midtrans/callback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']);


Route::prefix('admin')->name('admin.')->group(function () {

    // ================= LOGIN =================
    Route::get('login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('login', [AuthController::class, 'login'])
        ->name('login.post');

    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
        


    // ================= PROTECTED ROUTE =================
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/events', [AdminEventController::class, 'index'])
            ->name('events');

        Route::get('/transactions', [TransactionController::class, 'index'])
    ->name('transactions.index');

        Route::resource('events', AdminEventController::class);

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::put('/categories/{id}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');


        // ================= PARTNER =================
        Route::get('/partners', [PartnerController::class, 'index']);

        Route::post('/partners', [PartnerController::class, 'store']);

        Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])
            ->name('partners.edit');

        Route::put('/partners/{id}', [PartnerController::class, 'update'])
            ->name('partners.update');

        Route::delete('/partners/{id}', [PartnerController::class, 'destroy'])
            ->name('partners.destroy');

    });

});


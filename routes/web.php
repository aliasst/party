<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cabinet\ActController;
use App\Http\Controllers\Cabinet\AdminController;
use App\Http\Controllers\Cabinet\ClientController;
use App\Http\Controllers\Cabinet\ContractorController;
use App\Http\Controllers\Cabinet\EventController;
use App\Http\Controllers\Cabinet\InvoiceController;
use App\Http\Controllers\Cabinet\PurchaseController;
use App\Http\Controllers\Cabinet\ReportController;
use App\Http\Controllers\Cabinet\StageController;
use App\Http\Controllers\SuperAdmin\CabinetController as SuperAdminCabinetController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\Cabinet\DashboardController;
use App\Http\Controllers\Cabinet\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/password/reset', [AuthController::class, 'showForgotForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Суперадмин
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    // Кабинеты
    Route::get('/cabinets', [SuperAdminCabinetController::class, 'index'])->name('cabinets.index');
    Route::get('/cabinets/create', [SuperAdminCabinetController::class, 'create'])->name('cabinets.create');
    Route::post('/cabinets', [SuperAdminCabinetController::class, 'store'])->name('cabinets.store');
    Route::get('/cabinets/{cabinet}', [SuperAdminCabinetController::class, 'show'])->name('cabinets.show');
    Route::get('/cabinets/{cabinet}/edit', [SuperAdminCabinetController::class, 'edit'])->name('cabinets.edit');
    Route::put('/cabinets/{cabinet}', [SuperAdminCabinetController::class, 'update'])->name('cabinets.update');
    Route::delete('/cabinets/{cabinet}', [SuperAdminCabinetController::class, 'destroy'])->name('cabinets.destroy');
});

// Суперадминские маршруты без контекста кабинета
Route::middleware(['auth', 'superadmin'])->prefix('super')->name('super.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('superadmins', SuperAdminController::class)->except(['show']);
});

// Основные маршруты кабинета (требуют контекст)
Route::middleware(['auth', 'cabinet.context'])->prefix('cabinet')->name('cabinet.')->group(function () {
    // Дашборд
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Профиль
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Администраторы кабинета
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
    Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
    Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
    Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');


    // Мероприятия
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}/tz', [EventController::class, 'showTz'])->name('events.show_tz');

    // Маршруты для счетов (вложенные в мероприятия)
    Route::prefix('events/{event}/invoices')->name('events.invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
    });

    // Акты (вложенные в мероприятия)
    Route::prefix('events/{event}/acts')->name('events.acts.')->group(function () {
        Route::get('/', [ActController::class, 'index'])->name('index');
        Route::get('/create', [ActController::class, 'create'])->name('create');
        Route::post('/', [ActController::class, 'store'])->name('store');
        Route::get('/{act}/edit', [ActController::class, 'edit'])->name('edit');
        Route::put('/{act}', [ActController::class, 'update'])->name('update');
        Route::delete('/{act}', [ActController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('events/{event}/stages')->name('events.stages.')->group(function () {
        Route::get('/', [StageController::class, 'index'])->name('index');
        Route::get('/create', [StageController::class, 'create'])->name('create');
        Route::get('/create/{parentId}', [StageController::class, 'create'])->name('create.child'); // для создания подэтапа
        Route::post('/', [StageController::class, 'store'])->name('store');
        Route::get('/{stage}/edit', [StageController::class, 'edit'])->name('edit');
        Route::put('/{stage}', [StageController::class, 'update'])->name('update');
        Route::delete('/{stage}', [StageController::class, 'destroy'])->name('destroy');
        Route::post('/{stage}/files', [StageController::class, 'attachFiles'])->name('attachFiles');
        Route::delete('/{stage}/files/{file}', [StageController::class, 'deleteFile'])->name('deleteFile');
    });

    // Отчёты
    Route::prefix('events/{event}/reports')->name('events.reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/{stage}', [ReportController::class, 'show'])->name('show');
    });

    // Подрядчики
    Route::prefix('events/{event}/contractors')->name('events.contractors.')->group(function () {
        Route::get('/', [ContractorController::class, 'index'])->name('index');
        Route::get('/create', [ContractorController::class, 'create'])->name('create');
        Route::post('/', [ContractorController::class, 'store'])->name('store');
        Route::get('/{contractor}/edit', [ContractorController::class, 'edit'])->name('edit');
        Route::put('/{contractor}', [ContractorController::class, 'update'])->name('update');
        Route::delete('/{contractor}', [ContractorController::class, 'destroy'])->name('destroy');
    });

    // Закупки
    Route::prefix('events/{event}/purchases')->name('events.purchases.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/create', [PurchaseController::class, 'create'])->name('create');
        Route::post('/', [PurchaseController::class, 'store'])->name('store');
        Route::get('/{purchase}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{purchase}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy'])->name('destroy');
    });

    // Заказчики
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');


});
Route::redirect('/cabinet', '/cabinet/dashboard')->name('cabinet.index');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/home', function () {
    return view('home'); // создайте home.blade.php
})->middleware('auth')->name('home');

Route::get('/', function () {
    return redirect()->route('login');
});

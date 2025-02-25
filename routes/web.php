<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\CustomerController;
use App\Http\Controllers\Pos\DefaultController;
use App\Http\Controllers\Pos\InvoiceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Pos\TrackingController;

Route::middleware('auth')->group(function () {

    // Admin Routes
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'index')->middleware(['auth'])->name('admin.index');
        Route::get('/admin/logout', 'destroy')->name('admin.logout');
        Route::get('/admin/profile', 'Profile')->name('admin.profile');
        Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
        Route::post('/store/profile', 'StoreProfile')->name('store.profile');
        Route::get('/change/password', 'ChangePassword')->name('change.password');
        Route::post('/update/password', 'UpdatePassword')->name('update.password');
    });

    // Customer Routes
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer/all', 'CustomerAll')->name('customer.all');
        Route::get('/customer/add', 'CustomerAdd')->name('customer.add');
        Route::get('/customer/invoice/{id}', 'CustomerInvoice')->name('customer.invoice');
        Route::get('/customer/preview/{id}', 'CustomerShow')->name('customer.preview');
        Route::get('/customer/{id}/export-to-excel', 'exportToExcel')->name('export.excel');
        Route::get('/customer/addweight/{id}', 'addweight')->name('customer.addweight');
        Route::get('/customer/print/{id}', 'printInvoice')->name('invoice.print');
        Route::post('/customer/store', 'CustomerStore')->name('customer.store');
        Route::get('/customer/edit/{id}', 'CustomerEdit')->name('customer.edit');
        Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');
        Route::post('/customer/updateweight', 'CustomerUpdateWeight')->name('customer.updateweight');

        Route::get('/customer/delete/{id}', 'CustomerDelete')->name('customer.delete');
    });

    // Tracking Routes
    
    Route::controller(TrackingController::class)->group(function () {
        Route::get('/trackings', 'index')->name('trackings.index'); // List all trackings
        Route::get('/trackings/create', 'create')->name('trackings.create'); // Show the form to create a new tracking
        Route::post('/trackings', 'store')->name('trackings.store'); // Store a new tracking
        Route::get('/trackings/edit/{id}', 'edit')->name('trackings.edit'); // Show form to edit an existing tracking
        Route::put('/trackings/{id}', 'update')->name('trackings.update'); // Update an existing tracking
    });
    
    
});

require __DIR__.'/auth.php';

Route::get('/', function () {
    return redirect()->route('login');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\CustomerController;

use App\Http\Controllers\Pos\PaymentController;
use App\Http\Controllers\Pos\TrackingController;

use App\Http\Controllers\Pos\UsermgmtController;
use App\Http\Controllers\Pos\DispatchManagementController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Pos\AgenciesController;



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
        Route::get('/customer/recyclebin', 'recycle')->name('customer.recyclebin');
        Route::get('/restore/{id}','restore')->name('customer.restore');

      
    });

    // Tracking Routes

    Route::controller(TrackingController::class)->group(function () {
        Route::get('/trackings', 'index')->name('trackings.index'); // List all trackings
        Route::get('/trackings/create', 'create')->name('trackings.create'); // Show the form to create a new tracking
        Route::post('/trackings', 'store')->name('trackings.store'); // Store a new tracking
        Route::get('/trackings/edit/{id}', 'edit')->name('trackings.edit'); // Show form to edit an existing tracking
        Route::put('/trackings/{id}', 'update')->name('trackings.update'); // Update an existing tracking
    });

    Route::controller(PaymentController::class)->group(function () {
        Route::get('/payments', 'index')->name('payments.index')->middleware('role:super-admin');
        Route::get('/payments/details', 'details')->name('payments.details')->middleware('role:super-admin');
        Route::post('/payments/addexpenses', 'addexpenses')->name('expenses.store')->middleware('role:super-admin');
        Route::post('/payments/store', 'store')->name('payments.store')->middleware('role:super-admin');
        Route::put('/payments/edit/{id}', 'edit')->name('payments.edit')->middleware('role:super-admin');
        Route::put('/payments/{id}', 'update')->name('payments.update')->middleware('role:super-admin');
        Route::get('/payments/manage', 'manage')->name('payments.manage')->middleware('role:super-admin');
        Route::post('/payments/debits', 'debits')->name('payments.debits')->middleware('role:super-admin');
        Route::get('/payments/dashboard', 'dashboard')->name('payments.dashboard')->middleware('role:super-admin');
        Route::get('/payments/invoice/{id}', 'printInvoice')->name('payments.invoice')->middleware('role:super-admin');
        Route::post('/payments/invoice/store', 'InvoiceStore')->name('invoices.store')->middleware('role:super-admin'); 
    });

    Route::controller(UsermgmtController::class)->group(function () {
        Route::get('/usermgmt', 'index')->name('usermgmt.index');
        Route::post('/usermgmt/store', [UsermgmtController::class, 'store'])->name('usermgmt.store')->middleware('role:super-admin');
        Route::get("/usermgmt", [UsermgmtController::class, 'UserDetailsShow'])->name('usermgmt.index')->middleware('role:super-admin');
        Route::delete('/usermgmt/{id}', action: [UsermgmtController::class, 'destroy'])->name('usermgmt.destroy')->middleware('role:super-admin');
    });

    Route::controller(DispatchManagementController::class)->group(function () {
        Route::get('/dispatch', 'index')->name('dispatch.index'); // List all payments details
        Route::post('/dispatch/store', 'store')->name('dispatch.store');
    });
     

Route::controller(AgenciesController::class)->group(function () {
    Route::get('/agencies', 'index')->name('agencies.index');           // Show all agencies
    // Route::get('/agencies/create', 'create')->name('agencies.create');  // Show form to create
    // Route::post('/agencies/store', 'store')->name('agencies.store');    // Store new agency
    
    // Route::get('/agencies/{id}/edit', 'edit')->name('agencies.edit');   // Edit form
    // Route::put('/agencies/{id}', 'update')->name('agencies.update');    // Update agency
    // Route::delete('/agencies/{id}', 'destroy')->name('agencies.destroy'); // Delete agency
   Route::get('/agencies/{id}', 'show')->name('agencies.show');
});


});

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return redirect()->route('login');
});

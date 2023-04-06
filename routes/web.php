<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/lang',[
    'uses' => 'App\Http\Controllers\HomeController@lang',
    'as' => 'lang.index'
]);

Route::get('/', function () {
    try {
        DB::connection()->getPdo();
        if (!Schema::hasTable('application_settings'))
            return redirect('/install');
    } catch (\Exception $e) {
        return redirect('/install');
    }
    return redirect('dashboard');
});

Route::get('/clear', function() {
    $exitCode = Artisan::call('config:clear');
    echo $exitCode;
});


Auth::routes(['register' => false]);

Route::get('/install',[
    'uses' => 'App\Http\Controllers\InstallController@index',
    'as' => 'install.index'
]);

Route::post('/install',[
    'uses' => 'App\Http\Controllers\InstallController@install',
    'as' => 'install.install'
]);


Route::group(['middleware' => ['auth']], function() {
    Route::get('/company/companyAccountSwitch', [
        'uses' => 'App\Http\Controllers\CompanyController@companyAccountSwitch',
        'as' => 'company.companyAccountSwitch'
    ]);

    Route::get('/bill/getAddPaymentDetails',[
        'uses' => 'App\Http\Controllers\BillController@getAddPaymentDetails',
        'as' => 'bill.getAddPaymentDetails'
    ]);

    Route::post('/bill/addPaymentStore',[
        'uses' => 'App\Http\Controllers\BillController@addPaymentStore',
        'as' => 'bill.addPaymentStore'
    ]);

    Route::get('/invoice/getAddPaymentDetails',[
        'uses' => 'App\Http\Controllers\InvoiceController@getAddPaymentDetails',
        'as' => 'invoice.getAddPaymentDetails'
    ]);

    Route::post('/invoice/addPaymentStore',[
        'uses' => 'App\Http\Controllers\InvoiceController@addPaymentStore',
        'as' => 'invoice.addPaymentStore'
    ]);

    Route::get('/report/income',[
        'uses' => 'App\Http\Controllers\ReportController@income',
        'as' => 'report.income'
    ]);

    Route::get('/report/expense',[
        'uses' => 'App\Http\Controllers\ReportController@expense',
        'as' => 'report.expense'
    ]);

    Route::get('/report/incomeVsexpense',[
        'uses' => 'App\Http\Controllers\ReportController@incomeVsexpense',
        'as' => 'report.incomeVsexpense'
    ]);

    Route::get('/report/profitAndloss',[
        'uses' => 'App\Http\Controllers\ReportController@profitAndloss',
        'as' => 'report.profitAndloss'
    ]);

    Route::get('/report/tax',[
        'uses' => 'App\Http\Controllers\ReportController@tax',
        'as' => 'report.tax'
    ]);

    Route::get('users/{user}/read-items', 'App\Http\Controllers\UserController@readItemsOutOfStock');

    Route::resources([
        'roles' => App\Http\Controllers\RoleController::class,
        'users' => App\Http\Controllers\UserController::class,
        'customer' => App\Http\Controllers\CustomerController::class,
        'revenue' => App\Http\Controllers\RevenueController::class,
        'vendor' => App\Http\Controllers\VendorController::class,
        'payment' => App\Http\Controllers\PaymentController::class,
        'currency' => App\Http\Controllers\CurrencyController::class,
        'category' => App\Http\Controllers\CategoryController::class,
        'tax' => App\Http\Controllers\TaxController::class,
        'smtp' => App\Http\Controllers\SmtpConfigurationController::class,
        'company' => App\Http\Controllers\CompanyController::class,
        'invoice' => App\Http\Controllers\InvoiceController::class,
        'bill' => App\Http\Controllers\BillController::class,
        'item' => App\Http\Controllers\ItemController::class,
        'account' => App\Http\Controllers\AccountController::class,
        'transfer' => App\Http\Controllers\TransferController::class,
        'transaction' => App\Http\Controllers\TransactionController::class,
        'offline-payment' => App\Http\Controllers\OfflinePaymentController::class,
    ]);

    Route::get('/getItems', 'App\Http\Controllers\InvoiceController@getItems')->name('invoice.getItems');
    Route::get('/getBillItems', 'App\Http\Controllers\BillController@getBillItems')->name('bill.getBillItems');
    //Route::get('/getProduct', 'App\Http\Controllers\ProductController@getProduct')->name('product.getProduct');

    Route::get('patient-appointments/get-schedule/doctorwise', [App\Http\Controllers\PatientAppointmentController::class, 'getScheduleDoctorWise'])->name('patient-appointments.getScheduleDoctorWise');

    Route::post('/invoice/generateItemData',[
        'uses' => 'App\Http\Controllers\InvoiceController@generateItemData',
        'as' => 'invoice.generateItemData'
    ]);

    Route::post('/labreport/generateTemplateData',[
        'uses' => 'App\Http\Controllers\LabReportController@generateTemplateData',
        'as' => 'labreport.generateTemplateData'
    ]);

    Route::get('/c/c', [App\Http\Controllers\CurrencyController::class, 'code'])->name('currency.code');

    Route::get('/profile/setting',[
        'uses' => 'App\Http\Controllers\ProfileController@setting',
        'as' => 'profile.setting'
    ]);

    Route::post('/profile/updateSetting',[
        'uses' => 'App\Http\Controllers\ProfileController@updateSetting',
        'as' => 'profile.updateSetting'
    ]);
    Route::get('/profile/password',[
        'uses' => 'App\Http\Controllers\ProfileController@password',
        'as' => 'profile.password'
    ]);

    Route::post('/profile/updatePassword',[
        'uses' => 'App\Http\Controllers\ProfileController@updatePassword',
        'as' => 'profile.updatePassword'
    ]);
    Route::get('/profile/view',[
        'uses' => 'App\Http\Controllers\ProfileController@view',
        'as' => 'profile.view'
    ]);

});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard',[
    'uses' => 'App\Http\Controllers\DashboardController@index',
    'as' => 'dashboard'
    ]);
});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/apsetting',[
    'uses' => 'App\Http\Controllers\ApplicationSettingController@index',
    'as' => 'apsetting'
    ]);

    Route::post('/apsetting/update',[
    'uses' => 'App\Http\Controllers\ApplicationSettingController@update',
    'as' => 'apsetting.update'
    ]);
});

// general Setting
Route::group(['middleware' => ['auth']], function() {

    Route::get('/general',[
    'uses' => 'App\Http\Controllers\GeneralController@index',
    'as' => 'general'
    ]);

    Route::post('/general',[
    'uses' => 'App\Http\Controllers\GeneralController@edit',
    'as' => 'general'
    ]);

    Route::post('/general/localisation',[
    'uses' => 'App\Http\Controllers\GeneralController@localisation',
    'as' => 'general.localisation'
    ]);

    Route::post('/general/invoice',[
    'uses' => 'App\Http\Controllers\GeneralController@invoice',
    'as' => 'general.invoice'
    ]);

    Route::post('/general/bill',[
        'uses' => 'App\Http\Controllers\GeneralController@bill',
        'as' => 'general.bill'
    ]);

    Route::post('/general/defaults',[
    'uses' => 'App\Http\Controllers\GeneralController@defaults',
    'as' => 'general.defaults'
    ]);

});

Route::get('/home', function() {
    return redirect()->to('dashboard');
});

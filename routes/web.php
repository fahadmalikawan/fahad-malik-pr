<?php

use App\Helpers\ActiveUsersMailHelper;
use App\Helpers\StoreExternalApiData;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TestApiRecordController;
use App\Mail\ActiveUsersMail;
use App\Mail\GeneralMail;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', [LoginController::class, 'showAdminLoginForm'])->name('admin.login-view');
    Route::post('/login', [LoginController::class, 'adminLogin'])->name('admin.login');

    Route::get('/register', [RegisterController::class, 'showAdminRegisterForm'])->name('admin.register-view');
    Route::post('/register', [RegisterController::class, 'createAdmin'])->name('admin.register');

    Route::group(['middleware' => 'auth:admin'], function () {
        Route::get('/', function () {
            return view('admin-home');
        });
        Route::get('store-external-api-data', [StoreExternalApiData::class, 'storeExternalApiData'])->name('store_external_api_data');
        Route::get('test-email', [ActiveUsersMailHelper::class, 'mailActiveUsers'])->name('mail_active_users');
    });
});

Route::get('test-general-mail', function() {
    Mail::to('fahadmalik975@gmail.com')->send(new GeneralMail('Sub', 'Title', 'Name', 'Body'));
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('contacts', [ContactController::class, 'index'])->name('contacts');
    Route::post('/contacts/upload', [ContactController::class, 'uploadCsv'])->name('contacts_upload_csv');
});

<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
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

Route::get('/', 'HomeController@index');
Route::get('/payment', 'HomeController@paymentPage');
Route::get('/about-us', 'HomeController@aboutUsPage');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');



Route::resource('home', 'HomeController');
Route::resource('member', 'MemberController');
Route::resource('account/card', 'AccountCardController');
Route::resource('subscription', 'SubscriptionController');
Route::resource('ticket', 'TicketController');
Route::resource('account', 'ProfileController');
Route::resource('funfriday', 'EventController');
Route::get('funfriday/{slug}', 'EventController@show');

// Datatable member
Route::get('datatables/subscription/', 'SubscriptionController@datatables');
Route::get('datatables/ticket/', 'TicketController@datatables');
// end

Route::get('register', [AuthController::class, 'index'])->name('register');
Route::post('/create-user', [AuthController::class, 'create']);
// Start : Web
Route::get('/forgot-password', function () {
    return view('auth.passwords.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [ResetPasswordController::class, 'forgotPassword']);
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetPasswordForm']);
Route::post('reset-password', [ResetPasswordController::class, 'submitResetPasswordForm']);

Auth::routes();
Route::get('email/notice', 'Auth\VerificationController@show')->name('verification.notice');
Route::post('email/verify', 'Auth\VerificationController@resend')->name('verification.resend');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

/* Start : Admin Section */
Route::resource('admin/profile', 'Admin\ProfileController');
Route::resource('user', 'Admin\UserController');
Route::resource('dashboard', 'Admin\DashboardController');

// Start : Master Controller
Route::resource('master/member', 'Admin\Master\MemberController');
Route::resource('master/product', 'Admin\Master\ProductController');
Route::resource('master/merchant', 'Admin\Master\MerchantController');
Route::resource('master/bank_account', 'Admin\Master\BankAccountController');


/* Start : DataTables Section */
Route::get('datatables/user/', 'Admin\UserController@datatables');
Route::get('datatables/master/product/', 'Admin\Master\ProductController@datatables');
Route::get('datatables/master/member/', 'Admin\Master\MemberController@datatables');
Route::get('datatables/master/merchant/', 'Admin\Master\MerchantController@datatables');
Route::get('datatables/master/bank_account/', 'Admin\Master\BankAccountController@datatables');

// Start : Transaction
Route::resource('bookings', 'Admin\Transaction\BookingController');
Route::resource('booking_reminder', 'Admin\Transaction\BookingReminderController');
Route::resource('ticketing', 'Admin\Transaction\TicketingController');

// Start : Web Content
Route::resource('web_content', 'Admin\WebContentController');
Route::resource('event', 'Admin\EventController');

// DataTables Transaction
Route::get('datatables/bookings/', 'Admin\Transaction\BookingController@datatables');
Route::get('datatables/booking_reminder/', 'Admin\Transaction\BookingReminderController@datatables');
Route::get('datatables/ticketing/', 'Admin\Transaction\TicketingController@datatables');


// Default Controller
Route::get('setup_periode', 'HomeController@setup_periode');
Route::get('getDataBookingReminder', 'Admin\Transaction\BookingReminderController@getDataBookingReminder');
Route::get('sendReminder', 'Admin\Transaction\BookingReminderController@sendReminder');
Route::post('ticketing/reply', 'Admin\Transaction\TicketingController@ticketing_reply');
Route::get('datatables/web_content/', 'Admin\WebContentController@datatables');
Route::get('datatables/event/', 'Admin\EventController@datatables');
Route::post('event/update_status/{id}', 'Admin\EventController@updateStatus');

// Export Controller
Route::get('exportBookingReminder', 'Admin\Transaction\BookingReminderController@exportToExcel');
Route::get('exportMember', 'Admin\Master\MemberController@exportToExcel');
Route::put('reset_password/user', 'Admin\UserController@reset_password');
Route::put('reset_password/member', 'Admin\Master\MemberController@reset_password');

// Start : Jobs Controller
Route::get('auto_complete_booking', 'BookingController@auto_complete_booking');
    // End : Jobs Controller

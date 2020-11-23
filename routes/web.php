<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SnowboardController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

// HOME
Route::get('/', [HomeController::class, 'index']);

// ABOUT
Route::get('/about', [AboutController::class, 'index']);

// REGISTER
Route::get('/register/create', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'createRegister']);

// LOGIN
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'createLogin']);
Route::get('/logout', [LoginController::class, 'createLogout'])->middleware('auth');

// USERS
Route::get('/users', [UserController::class, 'indexUsers'])->middleware('auth');
Route::get('/users/feed', [UserController::class, 'showFeed'])->middleware('auth');
Route::get('/users/following', [UserController::class, 'showFollowing'])->middleware('auth');
Route::get('/users/purchases', [UserController::class, 'showPurchases'])->middleware('auth');
Route::get('/users/reviews', [UserController::class, 'showAllCurrentUserReviews'])->middleware('auth');
Route::get('/users/profile/me', [UserController::class, 'showCurrentUserProfile'])->middleware('auth');
Route::get('/users/profile/{userID}', [UserController::class, 'showProfile'])->middleware('auth');
Route::get('/users/cart', [UserController::class, 'showCart'])->middleware('auth');
Route::get('/users/changepassword/{id}', [UserController::class, 'showChangePassword'])->middleware('auth');
Route::get('/users/changeusername/{id}', [UserController::class, 'showChangeUsername'])->middleware('auth');
Route::get('/users/snowboards', [UserController::class, 'showCurrentUserSnowboards'])->middleware('auth');
Route::get('/users/snowboards/{id}', [UserController::class, 'showSnowboardUpdate'])->middleware('auth');
Route::get('/users/checkout', [UserController::class, 'showCheckout'])->middleware('auth');
Route::get('/users/paymentsuccess', [UserController::class, 'showPaymentSuccess'])->middleware('auth');
Route::post('/users/checkout/', [UserController::class, 'createCheckout']);
Route::post('/users/cart', [UserController::class, 'createCart']);
Route::delete('/users/cart/{id}', [UserController::class, 'destroyCartItem']);
Route::delete('/users/snowboards/{id}', [UserController::class, 'destroySnowboard']);
Route::patch('/users/snowboards/{id}', [UserController::class, 'updateSnowboard']);
Route::patch('/users/changepassword/{id}', [UserController::class, 'updatePassword']);
Route::patch('/users/changeusername/{id}', [UserController::class, 'updateUsername']);
Route::patch('/users/follow/{id}', [UserController::class, 'updateAddToFollowingList']);
Route::patch('/users/unfollow/{id}', [UserController::class, 'updateRemoveFromFollowingList']);
Route::patch('/users/following/{id}', [UserController::class, 'updateRemoveFromFollowingPage']);

// SNOWBOARDS
Route::get('/snowboards', [SnowboardController::class, 'index'])->middleware('auth');
// Route::post('/snowboards', [SnowboardController::class, 'createSort']);
Route::get('/snowboards/create', [SnowboardController::class, 'showSnowboardForm'])->middleware('auth');
Route::post('/snowboards/create', [SnowboardController::class, 'createSnowboardForm']);
Route::get('/snowboards/{id}', [SnowboardController::class, 'showDetails'])->middleware('auth');

// REVIEWS
Route::get('/reviews/create', [ReviewController::class, 'create'])->middleware('auth');
Route::get('/reviews/{id}', [ReviewController::class, 'show'])->middleware('auth');
Route::get('/reviews/update/{id}', [ReviewController::class, 'showUpdate'])->middleware('auth');
Route::post('/reviews/create', [ReviewController::class, 'store']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
Route::patch('/reviews/{id}', [ReviewController::class, 'update']);

//PURCHASES
Route::get('/payment/userpaymentinfo', [PurchaseController::class, 'showUserPaymentInfo'])->middleware('auth');
Route::post('/payment/userpaymentinfo', [PurchaseController::class, 'storePaymentInformation']);

// FORGOT PASSWORD
Route::get('/resetpassword', [ForgotPasswordController::class, 'showResetPassword']);
Route::get('/forgotpassword', [ForgotPasswordController::class, 'index']);
Route::get('/passwordpending', [ForgotPasswordController::class, 'showPending']);
Route::post('/forgotpassword', [ForgotPasswordController::class, 'store']);
Route::patch('/resetpassword', [ForgotPasswordController::class, 'updateResetPassword']);

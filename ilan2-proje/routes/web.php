<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GemiRouteController;
use App\Http\Controllers\YukController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Welcome sayfası - Giriş yapmamış kullanıcılar için
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Ana sayfa
Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return redirect()->route('welcome');
})->name('home');

// Guest rotaları
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.submit');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.submit');
});

// Auth gerektiren rotalar
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    // Profil yönetimi
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/type', [ProfileController::class, 'updateType'])->name('profile.update_type');
    Route::get('/profile/gemi-routes', [ProfileController::class, 'gemiRoutes'])->name('profile.gemi_routes');
    Route::get('/profile/yukler', [ProfileController::class, 'yukler'])->name('profile.yukler');
    
    // Gemi rotaları
    Route::get('/gemi-routes', [GemiRouteController::class, 'index'])->name('gemi_routes.index');
    Route::get('/gemi-routes/create', [GemiRouteController::class, 'create'])->name('gemi_routes.create');
    Route::post('/gemi-routes', [GemiRouteController::class, 'store'])->name('gemi_routes.store');
    Route::get('/gemi-routes/{gemiRoute}', [GemiRouteController::class, 'show'])->name('gemi_routes.show');
    Route::get('/gemi-routes/{gemiRoute}/edit', [GemiRouteController::class, 'edit'])->name('gemi_routes.edit');
    Route::put('/gemi-routes/{gemiRoute}', [GemiRouteController::class, 'update'])->name('gemi_routes.update');
    Route::delete('/gemi-routes/{gemiRoute}', [GemiRouteController::class, 'destroy'])->name('gemi_routes.destroy');
    Route::post('/gemi-routes/{gemiRoute}/match/{yuk}', [GemiRouteController::class, 'matchYuk'])->name('gemi_routes.match_yuk');
    
    // Yükler
    Route::get('/yukler', [YukController::class, 'index'])->name('yukler.index');
    Route::get('/yukler/create', [YukController::class, 'create'])->name('yukler.create');
    Route::post('/yukler', [YukController::class, 'store'])->name('yukler.store');
    Route::get('/yukler/{yuk}', [YukController::class, 'show'])->name('yukler.show');
    Route::get('/yukler/{yuk}/edit', [YukController::class, 'edit'])->name('yukler.edit');
    Route::put('/yukler/{yuk}', [YukController::class, 'update'])->name('yukler.update');
    Route::delete('/yukler/{yuk}', [YukController::class, 'destroy'])->name('yukler.destroy');
    Route::post('/yukler/{yuk}/request-match/{gemiRoute}', [YukController::class, 'requestMatch'])->name('yukler.request_match');
    Route::post('/yukler/{yuk}/cancel-match', [YukController::class, 'cancelMatch'])->name('yukler.cancel_match');
    Route::post('/yukler/{yuk}/complete-delivery', [YukController::class, 'completeDelivery'])->name('yukler.complete_delivery');

    // Harita
    Route::get('/map', [App\Http\Controllers\MapController::class, 'index'])->name('map.index');

});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/cargo', [AdminController::class, 'cargo'])->name('admin.cargo');
    Route::get('/routes', [AdminController::class, 'routes'])->name('admin.routes');
});

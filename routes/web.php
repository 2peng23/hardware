<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/admin-users', [AdminController::class, 'adminUsers'])->name('admin-users');
    Route::post('/add-user', [AdminController::class, 'addUser'])->name('add-user');
    Route::get('/deact-user/{id}', [AdminController::class, 'deactUser'])->name('deact-user');
    Route::get('/activate-user/{id}', [AdminController::class, 'activateUser'])->name('activate-user');
    Route::get('/inactive-user', [AdminController::class, 'inactiveUser'])->name('inactive-user');
    Route::get('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
    Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('edit-user');
    Route::put('/update-user', [AdminController::class, 'updateUser'])->name('update-user');

    // 
    Route::get('/categories', [ProductController::class, 'categories'])->name('categories');
    Route::post('/add-category', [ProductController::class, 'addCategory'])->name('add-category');
    Route::get('/items', [ProductController::class, 'items'])->name('items');
    Route::post('add-product', [ProductController::class, 'addProduct'])->name('add-product');
    Route::get('/edit-stock/{id}', [ProductController::class, 'editStock'])->name('edit-stock');
    Route::put('/add-stock', [ProductController::class, 'addStock'])->name('add-stock');
    Route::get('/edit-critical/{id}', [ProductController::class, 'editCritical'])->name('edit-critical');
    Route::put('/update-critical', [ProductController::class, 'updateCritical'])->name('update-critical');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

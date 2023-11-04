<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Auth;
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
    if (Auth::user()) {
        return redirect('/dashboard');
    }
    return view('auth.login');
});
Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {


    // Can be accessed by admin only
    Route::middleware('adminOnly')->group(function () {
        Route::get('/admin-users', [AdminController::class, 'adminUsers'])->name('admin-users');
        Route::get('/fetch-users', [AdminController::class, 'fetchUser'])->name('fetch-users');
        Route::get('/fetch-inactiveUsers', [AdminController::class, 'fetchInactiveUser'])->name('fetch-inactiveUsers');
        Route::post('/add-user', [AdminController::class, 'addUser'])->name('add-user');
        Route::get('/deact-user/{id}', [AdminController::class, 'deactUser'])->name('deact-user');
        Route::get('/activate-user/{id}', [AdminController::class, 'activateUser'])->name('activate-user');
        Route::get('/inactive-user', [AdminController::class, 'inactiveUser'])->name('inactive-user');
        Route::get('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('delete-user');
        Route::get('/edit-user/{id}', [AdminController::class, 'editUser'])->name('edit-user');
        Route::put('/update-user', [AdminController::class, 'updateUser'])->name('update-user');

        // category
        Route::get('/categories', [ProductController::class, 'categories'])->name('categories');
        Route::post('/add-category', [ProductController::class, 'addCategory'])->name('add-category');

        // items
        Route::get('/pagination/paginate-data', [ProductController::class, 'pagination']);
        Route::get('search-product', [ProductController::class, 'search'])->name('search-product');
        Route::get('/items', [ProductController::class, 'items'])->name('items');
        Route::post('add-product', [ProductController::class, 'addProduct'])->name('add-product');
        Route::get('/edit-product/{id}', [ProductController::class, 'editProduct'])->name('edit-product');
        Route::put('/update-product', [ProductController::class, 'updateProduct'])->name('update-product');
        Route::get('delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('delete-product');
        Route::get('/edit-stock/{id}', [ProductController::class, 'editStock'])->name('edit-stock');
        Route::put('/add-stock', [ProductController::class, 'addStock'])->name('add-stock');
        Route::get('/edit-critical/{id}', [ProductController::class, 'editCritical'])->name('edit-critical');
        Route::put('/update-critical', [ProductController::class, 'updateCritical'])->name('update-critical');

        // inventory
        Route::get('inventory', [ProductController::class, 'inventory'])->name('inventory');
        Route::get('search-inventory', [ProductController::class, 'searchInventory'])->name('search-inventory');
    });


    // staff
    Route::post('add-request', [StaffController::class, 'addRequest'])->name('add-request');
    Route::get('view-transaction/{id}', [StaffController::class, 'viewTransaction'])->name('view-transaction');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\ImportProductsController;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Front\CartController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth:admin,web'],
    'as' => 'dashboard.',
    'prefix' => 'admin/dashboard',
    //'namespace'
],function (){
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/',[DashboardController::class,'index'])
        ->name('dashboard');
    Route::get('/categories/trash',[CategoriesController::class,'trash'])
        ->name('categories.trash');
    Route::put('/categories/{category}/restore',[CategoriesController::class,'restore'])
        ->name('categories.restore');
    Route::get('/categories/{category}/force-delete',[CategoriesController::class,'forceDelete'])
        ->name('categories.force-delete');


    Route::get('products/import', [ImportProductsController::class, 'create'])
        ->name('products.import');
    Route::post('products/import', [ImportProductsController::class, 'store']);

    Route::resources([
        'products'=>ProductsController::class,
        'categories'=> CategoriesController::class,
        'roles'=>RolesController::class,
        'users'=>UsersController::class,
        'admins'=>AdminsController::class,
    ]);
});



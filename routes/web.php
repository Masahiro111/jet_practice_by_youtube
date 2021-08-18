<?php

use App\Http\Controllers\CrudController;
use App\Http\Controllers\PostController;
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

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

Route::get('/post', [PostController::class, 'index']);

Route::get('/post/create', [PostController::class, 'create']);

Route::post('/post', [PostController::class, 'store']);

Route::get('/post/{id}/edit', [PostController::class, 'edit']);

Route::put('/post/{id}', [PostController::class, 'update']);

Route::delete('/post/{id}', [PostController::class, 'destroy']);


// ---------------------------------------------------------------------

Route::get('/crud', [CrudController::class, 'index']);

Route::get('/crud/create', [CrudController::class, 'create']);

Route::post('/crud', [CrudController::class, 'store']);

Route::get('/crud/{id}/edit', [CrudController::class, 'edit']);

Route::put('/crud/{id}', [CrudController::class, 'update']);

Route::delete('/crud/{id}', [CrudController::class, 'destroy']);

<?php

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

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
//Route::get('/books', [App\Http\Controllers\BookController::class, 'index']);
//Route::get('/members', [App\Http\Controllers\MemberController::class, 'index']);

//Catalog
Route::get('/catalogs', [App\Http\Controllers\CatalogController::class, 'index']);
Route::get('/catalogs/create', [App\Http\Controllers\CatalogController::class, 'create']);
Route::post('/catalogs', [App\Http\Controllers\CatalogController::class, 'store']);
Route::get('/catalogs/{catalog}/edit', [App\Http\Controllers\CatalogController::class, 'edit']);
Route::put('/catalogs/{catalog}', [App\Http\Controllers\CatalogController::class, 'update']);
Route::delete('/catalogs/{catalog}', [App\Http\Controllers\CatalogController::class, 'destroy']);

/*Route::get('/publishers', [App\Http\Controllers\PublisherController::class, 'index']);
Route::get('/publishers/create', [App\Http\Controllers\PublisherController::class, 'create']);
Route::post('/publishers', [App\Http\Controllers\PublisherController::class, 'store']);
Route::get('/publishers/{publisher}/edit', [App\Http\Controllers\PublisherController::class, 'edit']);
Route::put('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'update']);
Route::delete('/publishers/{publisher}', [App\Http\Controllers\PublisherController::class, 'destroy']);*/

//Publisher
Route::get('/api/publishers', [App\Http\Controllers\PublisherController::class, 'api']);
Route::resource('/publishers',App\Http\Controllers\PublisherController::class);
//tabel publisher menggunakan route resource

//Author
Route::get('/api/authors', [App\Http\Controllers\AuthorController::class, 'api']);
Route::resource('/authors',App\Http\Controllers\AuthorController::class);

//Member
Route::get('/api/members', [App\Http\Controllers\MemberController::class, 'api']);
Route::resource('/members',App\Http\Controllers\MemberController::class);

//Book
Route::get('/api/books', [App\Http\Controllers\BookController::class, 'api']);
Route::resource('/books',App\Http\Controllers\BookController::class);

//Transaction
Route::resource('/transactions',App\Http\Controllers\TransactionController::class);
Route::get('/api/transactions', [App\Http\Controllers\TransactionController::class, 'api']);

//Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'dashboard']);

Route::get('/home/test_spatie', [App\Http\Controllers\HomeController::class, 'test_spatie']);
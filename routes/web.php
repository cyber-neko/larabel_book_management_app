<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BooksController;

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

/**
 * 認証チェック
 * groupでログインしていないと表示できないページを設定
 */
// Route::group(['middleware' => 'auth'], function () {
//     Route::get('/', function () {
//         return view('welcome');
//     });
// });

/**
 * ダッシュボード表示(books.blade.php)
 */
Route::get('/', [BooksController::class, 'index']);

/**
 * 本を追加 
 */
Route::post('/books', [BooksController::class, 'store']);

/**
 * 更新画面
 */
Route::post('/booksedit/{books}', [BooksController::class, 'edit']);

/**
 * 更新処理
 */
Route::post('/books/update', [BooksController::class, 'update']);

/**
 * 本を削除 
 */
Route::delete('/book/{book}', [BooksController::class, 'destroy']);

Auth::routes();

Route::get('/home', [BooksController::class, 'index'])->name('home');

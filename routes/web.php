<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;

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

//デフォルトコード
// Route::get('/', function () {
//     return view('welcome');
// });

/**
 * 本の一覧表示(books.blade.php)
 */
Route::get('/', function () {
    return view('books');
});

/**
 * 本を追加 
 */
Route::post('/books', function (Request $request) {
    //バリデーション
    //ルールを記載する
    $validator = Validator::make($request->all(), [
        'item_name' => 'required|max:255|min3',
    ]);

    //バリデーション:エラー 
    //withInput(): セッションに入力値を保存
    //エラー内容を$error変数に渡す
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    // Eloquentモデル（登録処理）
    $books = new Book;
    $books->item_name = $request->item_name;
    $books->item_number = '1';
    $books->item_amount = '1000';
    $books->published = '2017-03-07 00:00:00';
    $books->save();
    return redirect('/');
});

/**
 * 本を削除 
 */
Route::delete('/book/{book}', function (Book $book) {
    //
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

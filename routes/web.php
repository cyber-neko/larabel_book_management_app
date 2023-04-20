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
    $books = Book::orderBy('created_at', 'asc')->get();
    // view関数は第2引数に使用するデータを配列で渡す
    // return view('books');
    return view('books', [
        'books' => $books
    ]);
    //return view('books',compact('books')); //も同じ意味
});

/**
 * 本を追加 
 */
Route::post('/books', function (Request $request) {
    // dd($request);
    //バリデーション
    //ルールを記載する
    $validator = Validator::make($request->all(), [
        'item_name' => 'required|min:3|max:255',
        'item_number' => 'required|min:1|max:3',
        'item_amount' => 'required|max:6',
        'published' => 'required'
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

//更新画面
Route::post('/booksedit/{books}', function (Book $books) {
    //{books}id 値を取得 => Book $books id 値の1レコード取得
    return view('booksedit', ['book' => $books]);
});

//更新処理
Route::post('/books/update', function (Request $request) {
    //バリデーション
    $validator = Validator::make($request->all(), [
        'id' => 'required',
        'item_name' => 'required|min:3|max:255',
        'item_number' => 'required|min:1|max:3',
        'item_amount' => 'required|max:6',
        'published' => 'required',
    ]);
    //バリデーション:エラー
    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    //データ更新
    $books = Book::find($request->id);
    $books->item_name   = $request->item_name;
    $books->item_number = $request->item_number;
    $books->item_amount = $request->item_amount;
    $books->published   = $request->published;
    $books->save();
    return redirect('/');
});

/**
 * 本を削除 
 */
Route::delete('/book/{book}', function (Book $book) {
    $book->delete();
    return redirect('/');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

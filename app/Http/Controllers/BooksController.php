<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'asc')
            ->paginate(3);
        // view関数は第2引数に使用するデータを配列で渡す
        return view('books', [
            'books' => $books
        ]);
        //return view('books',compact('books')); //も同じ意味
    }

    public function edit($book_id)
    {
        //{books}id 値を取得 => Book $books id 値の1レコード取得
        $books = Book::where('user_id', Auth::user()->id)->find($book_id);
        return view('booksedit', [
            'book' => $books
        ]);
    }

    public function update(Request $request)
    {
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
        $books = Book::where('user_id', Auth::user()->id)->find($request->id);
        $books->user_id  = Auth::user()->id;
        $books->item_name   = $request->item_name;
        $books->item_number = $request->item_number;
        $books->item_amount = $request->item_amount;
        $books->published   = $request->published;
        $books->save();
        return redirect('/');
    }

    //登録
    public function store(Request $request)
    {
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

        $file = $request->file('item_img'); //file取得
        if (!empty($file)) {                //fileが空かチェック
            $filename = $file->getClientOriginalName();   //ファイル名を取得
            $move = $file->move('../public/upload/', $filename);  //ファイルを移動
        } else {
            $filename = "";
        }

        // Eloquentモデル（登録処理）
        $books = new Book;
        $books->user_id  = Auth::user()->id;
        $books->item_name = $request->item_name;
        $books->item_number = $request->item_number;
        $books->item_amount = $request->item_amount;
        $books->item_img = $filename;
        $books->published = $request->published;
        $books->save();
        return redirect('/')->with('message', '本登録が完了しました'); //'message'変数に値を登録
    }

    //削除
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/');
    }
}

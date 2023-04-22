<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use Faker\Factory;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create('ja_JP'); //Faker:テストデータ自動生成ライブラリ, ja_JP:テストデータで日本語を利用可能にする
        for ($i = 0; $i < 10; $i++) {
            Book::create([
                'item_name' => $faker->word(), //文字列
                'user_id' => $faker->numberBetween(1, 2), //1〜2
                'item_number' => $faker->numberBetween(1, 999), //1〜999
                'item_amount' => $faker->numberBetween(100, 5000), //100〜5000
                'item_img' => $faker->image("./public/upload/", 300, 300, 'cats', false), //ルートからのパス、絶対パスで対応
                'published' => $faker->dateTime('now'), //現在までYmdHis
                'created_at' => $faker->dateTime('now'), //現在までYmdHis
                'updated_at' => $faker->dateTime('now'), //現在までYmdHis
            ]);
        }
    }
}

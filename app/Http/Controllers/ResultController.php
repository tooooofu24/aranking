<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Student;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function all_results()
    {
        $limit = new DateTime('2021-09-30');
        if (Carbon::now() < $limit) {
            return view('wait');
        }
        $results = [];
        $questions = Question::all();
        $students = Student::all();
        for ($i = 10; $i <= 40; $i++) {
            $question = DB::table('questions')->where('key', $i)->first()->text;

            $datas = DB::table('answers')
                ->select('students.name', DB::raw("count(q$i) as `count`"))
                ->groupBy("q$i")
                ->orderBy("count", 'desc', "q$i")
                ->leftJoin('students', 'students.number', '=', "answers.q$i")
                ->get();
            $results[] = [
                'question' => $question,
                'datas' => $datas
            ];
        }
        $pokemon_id = strval(mt_rand(1, 809)); // 1から809のランダムな数字が $pokemon_id に入る
        $pokemon_url = "https://pokeapi.co/api/v2/pokemon/$pokemon_id/";
        $pokemon_json = file_get_contents($pokemon_url); // jsonデータを取得
        $pokemon_data = json_decode($pokemon_json); // jsonからオブジェクトに直す（扱いやすくする）
        $pokemon_img = $pokemon_data->sprites->front_default; // ポケモンの画像
        return view('all_results', compact('results', 'pokemon_img'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function showAnswersStatus()
    {
        $answers = Answer::all();
        $datas = [];
        foreach ($answers as $answer) {
            $array = [];
            for ($i = 10; $i <= 40; $i++) {
                $array[] = $answer["q$i"];
            }
            $array = array_filter($array);
            $count = count($array);
            $datas[] = [
                'name' => $answer->line_name,
                'updated_at' => $answer->updated_at ? $answer->updated_at->format('n/d H:i') : '',
                'count' => $count
            ];
        }
        return view('management.answer_status', compact('datas'));
    }
}

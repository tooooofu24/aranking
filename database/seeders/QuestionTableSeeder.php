<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = [
            10 => '優しい人は？',
            11 => 'おもしろい人は？',
            12 => '頼りになる人は？',
            13 => '暇人は？',
            14 => '天然なのは？',
            15 => 'マイペースな人は？',
            16 => '遅刻魔は？',
            17 => 'おしゃれな人は？',
            18 => '話しやすいのは？',
            19 => '怖いのは？',
            20 => '仕事人間は？',
            21 => '人気者は？',
            22 => '愛すべき問題児は？',
            23 => '結婚したい(男)',
            24 => '結婚したい(女)',
            25 => 'モテる',
            26 => 'フットワークが軽い',
            27 => '長男長女タイプ',
            28 => '末っ子タイプ',
            29 => '１日入れ替わるなら？',
            30 => '恋愛相談するなら？',
            31 => '20といえば？',
            32 => '19といえば？',
            33 => '18といえば？',
            34 => 'ミスターあらぐさは？',
            35 => 'ミスあらぐさは？',
            36 => 'あおぞらといえば？',
            37 => 'うぐいすといえば？',
            38 => 'しおかぜといえば？',
            39 => 'なかよしといえば？',
            40 => 'あらぐさといえば？',
        ];
        foreach ($questions as $k => $v) {
            DB::table('questions')->insert([
                'key' => $k,
                'text' => $v
            ]);
        }
    }
}

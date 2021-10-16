<?php

namespace App\Http\Controllers;

use App\Library\LineMessagingApi;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Student;
use App\Models\Test;
use Exception;
use Illuminate\Http\Request;
use LINE\LINEBot;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\UnfollowEvent;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineController extends Controller
{
    public function line(Request $request)
    {
        // アクセストークン
        $channel_access_token = env('CHANNEL_ACCESS_TOKEN');
        // チャンネルシークレット
        $channel_secret = env('CHANNEL_SECRET');

        // イベント取得
        $http_client = new CurlHTTPClient($channel_access_token);
        $bot = new LINEBot($http_client, ['channelSecret' => $channel_secret]);

        // $signature = $_SERVER['HTTP_' . HTTPHeader::LINE_SIGNATURE];
        $signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
        $events = $bot->parseEventRequest($request->getContent(), $signature);
        $event = $events[0];

        // リプライトークン（返信に必要）
        $replyToken = $event->getReplyToken();

        // LINEのユーザー識別子（uuid）
        $line_id = $event->getUserId();

        // LINEの表示名
        $profile = json_decode($bot->getProfile($line_id)->getRawBody(), true);
        $line_name = $profile["displayName"];

        // DBにデータがなければテーブル作成（友達追加時など）
        $answer = Test::where('line_id', $line_id)->first();
        if (!$answer) {
            $answer = new Test();
            $answer->line_id = $line_id;
            $answer->line_name = $line_name;
            $answer->save();
            $answer = Test::where('line_id', $line_id)->first();
        }

        // メインの処理 ---------------------------------------------------------------------------------------
        try {
            // 友達登録＆ブロック解除イベント
            if ($event instanceof FollowEvent) {
                // ポストバックイベントの時の処理
                exit;
            } elseif ($event instanceof PostbackEvent) {
                // $bot->replyText($replyToken, '集計が終わりました。「結果を見る」と送信するとあらんきんぐの結果が見れます！');
                $data = $event->getPostbackData();

                // 何も返答しないボタンには'-'が入っている
                if ($data == '-') {
                    exit;
                }

                // ポストバックイベントをint型に変換
                $data = intval($data);

                // $dataが一桁の場合の処理（回答を始めるボタンが押されたとき）
                if ($data == 1) { // 『回答を始める」
                    $builder = LineMessagingApi::selectGrade(10);
                    $bot->replyMessage($replyToken, $builder);
                } elseif ($data == 2) { // 「回答を始めない」
                    $bot->replyText($replyToken, 'キャンセルしました');
                } elseif ($data >= 10 && $data < 100) {
                    $builder = LineMessagingApi::selectGrade($data, 2);
                    $bot->replyMessage($replyToken, $builder);
                }

                // $dataが5桁の場合の処理（学年が選択されたとき）
                elseif ($data < 30000 && $data > 10000) {
                    $grade = $data % 100;
                    $students = Student::where('grade', $grade)->get();
                    $question_number = intval(substr($data, 1, 2));
                    if ($data > 20000) { // 回答の修正
                        $flexBuilder = LineMessagingApi::sendQuestion($students, $question_number, 2);
                    } else { // 連続で回答
                        $flexBuilder = LineMessagingApi::sendQuestion($students, $question_number);
                    }
                    $bot->replyMessage($replyToken, $flexBuilder);

                    // $dataが7桁(21011804など)の場合の処理（解答が選択されたとき）
                } elseif ($data > 1000000) {
                    // 学生の番号（回答）
                    $student_number = $data % 10000;
                    // 問題番号 
                    $question_number = intval(substr($data, 1, 2));
                    // 学生のサークルネーム
                    $student = Student::where('number', $student_number)->first()->name;
                    // 問題の文
                    $question = Question::where('key', $question_number)->first()->text;
                    // MultiBuilderに格納（複数メッセージ返信に使う）
                    $multiBuilder = new MultiMessageBuilder();
                    $textBuilder = new TextMessageBuilder("「${question}」\n${student}に投票したよ！");
                    $multiBuilder->add($textBuilder);
                    if ($data > 2000000) { // 解答修正の場合
                    } else { // 連続解答の場合
                        // 返信用MessageBuilder
                        if ($question_number < 40) {
                            // 次の問題を送るbuilder
                            $flexBuilder = LineMessagingApi::selectGrade($question_number + 1);
                            $multiBuilder->add($flexBuilder);
                        } else {
                            // 最後の回答であったら回答を終了する
                            $answerEndText = new TextMessageBuilder('全ての回答が終わりました！');
                            $multiBuilder->add($answerEndText);
                        }
                    }
                    // 解答の保存
                    $answer->update(['q' . $question_number => $student_number]);

                    // 返信
                    $bot->replyMessage($replyToken, $multiBuilder);
                }
                // メッセージイベント
            } elseif ($event instanceof MessageEvent) {
                $message = $event->getText();
                if ($message == '結果を見る') {
                    $bot->replyText($replyToken, 'https://aranking2021.sumomo.ne.jp/results');
                }
                if ($message == '回答を始める') {
                    $builder = LineMessagingApi::start();
                    $bot->replyMessage($replyToken, $builder);
                } elseif ($message == '回答を修正') {
                    $builder = LineMessagingApi::edit();
                    $bot->replyMessage($replyToken, $builder, 2);
                } elseif ($message == '回答を確認') {
                    $text = '';

                    // 解答１問ごとに解答状況を取得して$textに追加していく
                    foreach (Question::all() as $question) {
                        // 問題の文
                        $text .= $question->text . "\n";

                        // 学生の識別番号
                        $student_number = $answer["q" . strval($question->key)];

                        // 解答済みであったらその学生のサークルネーム、未解答であったら「未解答です」と入れる
                        if ($student_number) {
                            $student_name = Student::where('number', $student_number)->first()->name;
                        } else {
                            $student_name = '未解答';
                        }

                        // $textに追加（最後問題の場合改行しない）
                        if ($question->key == 40) {
                            $text .= "→ " . $student_name;
                        } else {
                            $text .= "→ " . $student_name . "\n\n";
                        }
                    }
                    // 解答状況をテキストで返信
                    $bot->replyText($replyToken, $text);
                } elseif ($message == '結果を見る') {
                    $bot->replyText($replyToken, '集計中です！');
                } elseif ($message == '使い方') {
                    $bot->replyText($replyToken, "「回答を始める」をタップすると全ての質問に回答できます！\n「修正」をタップすると回答を修正できます！\n「回答を確認」で回答の確認ができます！");
                } elseif ($message == 'エラー') {
                    throw new Exception('This message is thrown in the test');
                } else {
                    // 指定されていないメッセージが送信された場合
                    $bot->replyText($replyToken, "テキストの送信には対応していません！\n「使い方」と送信すると使い方が確認できます！");
                }
                // ブロックイベント
            } elseif ($event instanceof UnfollowEvent) {
                // ブロック・削除された際の動作（現状何も行わない）
            }

            // API側でバグがあり、何も返信できなかった場合はエラーを返す。
            $builder = LineMessagingApi::error('不明なエラーです。');
            $bot->replyMessage($replyToken, $builder);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $error_text = "システムエラーが発生しました。\n$error";
            $builder = LineMessagingApi::error($error_text);
            $bot->replyMessage($replyToken, $builder);
        }
    }
}

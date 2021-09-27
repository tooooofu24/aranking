<?php

namespace App\Library;

use App\Models\Question;
use App\Models\Student;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;

class LineMessagingApi
{

    // 回答開始のメッセージビルダー
    public static function start()
    {
        // 選択肢のアクションを設定
        $yes_confirm = new PostbackTemplateActionBuilder('はい', 1);
        $no_confirm = new PostbackTemplateActionBuilder('いいえ', 2);

        // 配列に格納
        $actions = [$yes_confirm, $no_confirm];

        // MessageBuilderの設定
        $confirm = new ConfirmTemplateBuilder('回答を始めますか？', $actions);
        $builder = new TemplateMessageBuilder('回答を始めますか？', $confirm);

        // MessageBuiderを返す
        return $builder;
    }

    // 学年を選ぶ際のメッセージビルダー
    public static function selectGrade(int $question, int $next = 1)
    {
        // 選択肢のアクションを設定
        $action_18 = new PostbackTemplateActionBuilder('18から選ぶ', strval($next) . strval($question) . '18');
        $action_19 = new PostbackTemplateActionBuilder('19から選ぶ', strval($next) . strval($question) . '19');
        $action_20 = new PostbackTemplateActionBuilder('20から選ぶ', strval($next) . strval($question) . '20');

        // 選択肢のボタンを設定
        $button_18 = new ButtonComponentBuilder($action_18);
        $button_19 = new ButtonComponentBuilder($action_19);
        $button_20 = new ButtonComponentBuilder($action_20);
        $button_18->setStyle('primary')->setMargin('md')->setColor('#808080');
        $button_19->setStyle('primary')->setMargin('md')->setColor('#808080');
        $button_20->setStyle('primary')->setMargin('md')->setColor('#808080');

        // 選択肢のボタンを配列に格納
        $buttons = [];
        $buttons[] = $button_18;
        $buttons[] = $button_19;
        $buttons[] = $button_20;

        // コンポーネントの設定
        $box = new BoxComponentBuilder('vertical', $buttons);
        $q = Question::where('key', $question)->first(); // 該当の問題
        $title = new TextComponentBuilder($q->text);

        // デザインの調整
        $title->setAlign('center');
        $title->setWeight('bold');
        $title->setSize('lg');
        $title->setOffsetTop('lg');

        // MessageBuilderの設定
        $header = new BoxComponentBuilder('vertical', [$title]);
        $bubble = new BubbleContainerBuilder();
        $bubble->setBody($box);
        $bubble->setHeader($header);
        $flexBuilder = new FlexMessageBuilder($q->text, $bubble);

        // MessageBuilderを返す
        return $flexBuilder;
    }

    // 問題を送るときのメッセージビルダー
    public static function sendQuestion($students, int $question_number, int $next = 1)
    {
        // 回答する問題
        $question = Question::where('key', $question_number)->first();
        $buttons = [];
        $bubbles = [];
        foreach ($students as $s) {
            // 選択肢のアクションを設定
            $action = new PostbackTemplateActionBuilder($s->name, strval($next) . strval($question_number) . strval($s->number));

            // ボタンの設定
            $button = new ButtonComponentBuilder($action);
            $button->setStyle('primary');
            $button->setMargin('md');
            // グループごとに色を設定
            $group = substr($s->number, 2, 1);
            $button->setColor(LineMessagingApi::selectColor($group));

            // buttonsに追加
            $buttons[] = $button;

            // buttonsに4つbuttonが入っていれば、コンポーネントとして追加
            if (count($buttons) == 4) {
                $box = new BoxComponentBuilder('vertical', $buttons);
                $title = new TextComponentBuilder($question->text);
                $title->setAlign('center');
                $title->setWeight('bold');
                $title->setSize('lg');
                $title->setOffsetTop('lg');
                $header = new BoxComponentBuilder('vertical', [$title]);
                $bubble = new BubbleContainerBuilder();
                $bubble->setBody($box);
                $bubble->setHeader($header);
                $bubbles[] = $bubble;
                $buttons = [];
            }
        }

        // buttonsにまだボタンが入っていれば空のボタンを入れて合計4つのボタンにする
        if (count($buttons) > 0) {
            $remainder = 4 - count($buttons) % 4;
            for ($i = 0; $i < $remainder; $i++) {
                // 空の選択肢には'-'を入れておく
                $action = new PostbackTemplateActionBuilder('-', '-');
                $button = new ButtonComponentBuilder($action);
                $button->setStyle('primary');
                $button->setMargin('md');
                $button->setColor('#FFFFFF');
                $buttons[] = $button;
            }
        }

        // 余りの分のbuttonsもbubblesに追加
        $box = new BoxComponentBuilder('vertical', $buttons);
        $title = new TextComponentBuilder($question->text);
        $title->setAlign('center');
        $title->setWeight('bold');
        $title->setSize('lg');
        $title->setOffsetTop('lg');
        $header = new BoxComponentBuilder('vertical', [$title]);
        $bubble = new BubbleContainerBuilder();
        $bubble->setBody($box);
        $bubble->setHeader($header);
        $bubbles[] = $bubble;

        // bubblesをカルーセルに追加
        $carousel = new CarouselContainerBuilder($bubbles);

        // MessageBuilderを設定
        $flexBuilder = new FlexMessageBuilder($question->text, $carousel);

        // MessageBuilderを返す
        return $flexBuilder;
    }

    // 解答の修正のときのメッセージビルダー
    public static function edit()
    {
        $buttons = [];
        $bubbles = [];

        // 問題を一つずつbuttonに格納
        foreach (Question::all() as $q) {
            // アクションの設定
            $action = new PostbackTemplateActionBuilder($q->text, strval($q->key));

            // コンポーネントに格納
            $button = new ButtonComponentBuilder($action);
            $button->setStyle('primary')->setMargin('md')->setColor('#808080');

            // buttonsに格納
            $buttons[] = $button;

            // buttonsが4つであればbubbleに入れる
            if (count($buttons) == 4) {
                $box = new BoxComponentBuilder('vertical', $buttons);
                $title = new TextComponentBuilder('どの回答を変更しますか？');
                $title->setAlign('center');
                $title->setWeight('bold');
                $title->setSize('lg');
                $title->setOffsetTop('lg');
                $header = new BoxComponentBuilder('vertical', [$title]);
                $bubble = new BubbleContainerBuilder();
                $bubble->setBody($box);
                $bubble->setHeader($header);
                $bubbles[] = $bubble;
                $buttons = [];
            }
        }

        // buttonsにまだボタンが入っていれば空のボタンを入れて合計4つのボタンにする
        if (count($buttons) > 0) {
            $remainder = 4 - count($buttons) % 4;
            for ($i = 0; $i < $remainder; $i++) {
                $action = new PostbackTemplateActionBuilder('-', '-');
                $button = new ButtonComponentBuilder($action);
                $button->setStyle('primary')->setMargin('md')->setColor('#FFFFFF');
                $buttons[] = $button;
            }
        }

        // 余りの分のbuttonsもbubblesに追加
        $box = new BoxComponentBuilder('vertical', $buttons);
        $title = new TextComponentBuilder('どの回答を変更しますか？');
        $title->setAlign('center');
        $title->setWeight('bold');
        $title->setSize('lg');
        $title->setOffsetTop('lg');
        $header = new BoxComponentBuilder('vertical', [$title]);
        $bubble = new BubbleContainerBuilder();
        $bubble->setBody($box);
        $bubble->setHeader($header);
        $bubbles[] = $bubble;

        // bubblesをカルーセルに追加
        $carousel = new CarouselContainerBuilder($bubbles);

        // MessageBuilderを設定
        $flexBuilder = new FlexMessageBuilder('どの回答を変更しますか？', $carousel);

        // MessageBuilderを返す
        return $flexBuilder;
    }

    // エラーを返すときのメッセージビルダー
    public static function error(string $error)
    {
        $frend_add_url = "https://line.me/ti/p/TYUvxm5qUS";
        $multiBuilder = new MultiMessageBuilder();
        $errorMessage = new TextMessageBuilder($error);
        $announceMessage = new TextMessageBuilder("お手数ですが下記のアカウントからLINEを追加していただき、管理者までお問い合わせください。");
        $urlMessage = new TextMessageBuilder($frend_add_url);
        $multiBuilder->add($errorMessage);
        $multiBuilder->add($announceMessage);
        $multiBuilder->add($urlMessage);
        return $multiBuilder;
    }

    public static function selectColor($group)
    {
        if ($group == 0) {
            // あおぞら
            $color = '#FF6600';
        } elseif ($group == 1) {
            // しおかぜ
            $color = '#00CCFF';
        } elseif ($group == 2) {
            // うぐいす
            $color = '#00CC00';
        } else {
            // なかよし
            $color = '#8A2BE2';
        }
        return $color;
    }
}

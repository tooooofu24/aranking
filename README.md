## 「あらんきんぐ」とは
私の所属しているサークルで定期的に行われているサークル内の番付けです。  
「〇〇な人は？」というような質問に何問か答えて、それを集計してランキングにします。  
[こちら](https://aranking2021.sumomo.ne.jp/results)を見ていただくとイメージがしやすいかと思います。  
今回私はLaravel×LINE Messaging APIであらんきんぐを作成しました。
## 作った理由
例年、あらんきんぐは紙で回答を集め、集計していました。  
しかし去年から、コロナの影響で紙での集計は難しいとのことで、Googleのアンケートフォームで集計を行うようになりました。
回答の集計自体は自動化できて良かったのですが、2つ問題点がありました。
- 大量の質問を一気に回答しなくてはいけないということ
- 1人で複数回答ができてしまうこと  

それらの問題点を解決するためにLINEbotでの実装をすることを決めました。
## サービスの機能
ここから私が実際に作ったLINEbotの紹介をします。「LINE上で完結する」ことを目標に作成しました。  
実際に「投票」、「回答の修正」、「結果の確認」まで全てLINE上でできるようになっています。    
<div align="center">
    <img width="200" src="https://user-images.githubusercontent.com/64852221/130006644-17669957-1b94-49d0-9abc-3b69f2f8066b.png">
    <img width="200" src="https://user-images.githubusercontent.com/64852221/130006970-7704ae9d-c058-461b-876d-6a1a05e0ed7f.png">
    <img width="200" src="https://user-images.githubusercontent.com/64852221/130007468-0ffe5725-cc64-4064-87f5-acaf872f415b.png">
    <img width="200" src="https://user-images.githubusercontent.com/64852221/130007481-7382df7c-27f2-4862-9200-c82603a0089e.png">
</div>


## 結果画面
[ランキングの結果画面](https://aranking2021.sumomo.ne.jp/results)になります。  
リロードするたびに表示されるポケモンが変わる仕様になっています。  
![image](https://user-images.githubusercontent.com/64852221/133055895-4452b567-eb5a-4fd2-a31c-a8d1becfb323.png)    
レスポンシブWebデザインにも対応しています。以下スマートフォンでの表示になります。  
<div align="center">
    <img width="200" src="https://user-images.githubusercontent.com/64852221/133056721-e101a699-a0a8-4e9b-95e7-37e170db1863.png" >  
</div>

## 回答状況確認画面
[回答状況確認画面](https://aranking2021.sumomo.ne.jp/answerStatus)になります。  
現在のサークル全体の回答状況が可視化できます。このページを使って回答の催促を行いました。
![image](https://user-images.githubusercontent.com/64852221/135433734-f2425691-a827-4a81-b2c9-d772f319c67e.png)

## 使用方法
下記ボタンもしくはQRコードからLINEbotを追加します。    
追加して「使い方」とLINEを送ると使い方のチュートリアルが見られます。  
「エラー」と送信するとエラーが発生したときのテストができます。  
<div align="center">
    <img src="https://user-images.githubusercontent.com/64852221/129901335-0a7f9bb1-db88-4182-8566-79b9c110389f.png">
</div>

## 工夫した点
##### APIの処理をLibraryにまとめた
LINEのAPIに関する記述をController内で全て書いてしまうと、可読性が下がり、オブジェクト指向的にも良くないということでコードを分離しました。APIに関する記述は[App\Library\LineMessagingApi.php](https://github.com/tooooofu24/arank/blob/master/app/Library/LineMessagingApi.php)で記載するようにしています。
##### グループごとに色分けをした。
回答をする際、4色に分かれているのがわかると思います。これはサークル内のグループで色分けをしており、新入生でも誰がどのグループなのか分かるデザインになっています。
##### 集計結果は既定の日時にならないと表示されない
既定の日時になるまでは「結果を見る」ボタンを押しても「集計中です」と返信が来るようになっており、仮に結果画面のurlにアクセスしたとしても、結果が見れないような仕様にしています。
##### 現在の回答数がわかる
サークルの代表に「回答状況がわかる画面が欲しい」と言われたので作りました。[回答管理画面](https://aranking2021.sumomo.ne.jp/management)にアクセスすると現在の回答状況が確認できます。
　　
## 改善点
##### レスポンスを全て数値で管理してしまった
LINE Messaging APIではレスポンスに文字列しか指定できません。keyとvalueの形で持つことができないので、私は誰がどんなリクエストを送ってきたのかを全て数字で管理しました。   
作り終えてからJson形式でレスポンスを管理すればもっと可読性が上がったと反省しています。

## 使用技術
- Laravel 8.61.0
- Bootstrap 5.0
- [LINE Messaging API](https://developers.line.biz/ja/services/messaging-api/)
- MySQL
- Git
- [Poke API](https://pokeapi.co/)

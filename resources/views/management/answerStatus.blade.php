<!DOCTYPE html>
<html lang="ja" style="height: 100%;">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CSS -->
    <link rel="stylesheet" href="/css/style.css" />
    <!-- BootStrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    <!-- Font Awsome -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">

    <title>あらんきんぐ回答状況</title>
</head>

<body style="height: 100%;">
    <header class="position-fixed w-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="navbar-brand wf-roundedmplus1c">あらんきんぐ回答状況</div>
            </div>
        </nav>
    </header>
    <main class="wf-roundedmplus1c d-flex align-items-center h-100">
        <div class="w-100">
            <div class="container">
                <div class="p-3">
                    <div class="my-3 mx-2">LINE友だち数 ({{$userCount}}人/67人)</div>
                    <div class="align-middle">
                        <div class="progress m-0" style="height: 30px;">
                            <div class="progress-bar" role="progressbar" style="max-width: {{floor(($userCount*100)/67)}}%; animation: bar-animation 1 3s; width: {{floor(($userCount*100)/67)}}%;" aria-valuenow="{{floor(($userCount*100)/67)}}" aria-valuemin="0" aria-valuemax="100">{{floor(($userCount*100)/67)}}%</div>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <div class="my-3 mx-2">回答済み質問数 ({{$answerCount}}問/{{31*67}}問)</div>
                    <div class="col align-middle">
                        <div class="progress m-0" style="height: 30px;">
                            <div class="progress-bar" role="progressbar" style="width: {{floor(($answerCount*100)/(67*31))}}%; max-width: {{floor(($answerCount*100)/(67*31))}}%; animation: bar-animation 1 3s;" aria-valuenow="{{floor(($answerCount*100)/(67*31))}}" aria-valuemin="0" aria-valuemax="100">{{floor(($answerCount*100)/(67*31))}}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <style>
        @keyframes bar-animation {
            0% {
                width: 0px;
            }

            100% {
                width: 100%;
            }
        }
    </style>
</body>

</html>
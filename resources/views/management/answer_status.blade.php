<!DOCTYPE html>
<html lang="ja">

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

    <title>回答管理画面</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="navbar-brand wf-roundedmplus1c">回答管理画面</div>
            </div>
        </nav>
    </header>
    <main class="container wf-roundedmplus1c">
        <article>
            <table class="table table-striped table-borderless mt-3" style="font-size: 11px;">
                <thead>
                    <tr>
                        <th style="width: 30%;">名前</th>
                        <th style="width: 20%;">回答数</th>
                        <th style="width: 20%;">未回答数</th>
                        <th style="width: 30%;">最終更新日時</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $data)
                    <tr>
                        <td>{{$data['name']}}</td>
                        <td>{{$data['count']}}</td>
                        <td>{{31-$data['count']}}</td>
                        <td>{{$data['updated_at']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </article>
    </main>
</body>

</html>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- CSS -->
    <link rel="stylesheet" href="/aranking/public/css/style.css" />
    <!-- BootStrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous" />
    <!-- Font Awsome -->
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet" />
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c" rel="stylesheet">

    <title>あらんきんぐ結果発表</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="navbar-brand wf-roundedmplus1c">あらんきんぐ結果発表</div>
            </div>
        </nav>
    </header>
    <main class="container wf-roundedmplus1c">
        <article>
            <div class="row">
                @foreach($results as $result)
                <div class="col-sm-6 py-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <h2 class="card-title text-center text-secondary fs-200">{{$result['question']}}</h2>
                            <div class="row">
                                <div class="col-4">
                                    <img src="{{$pokemon_img}}" alt="ポケモンの画像" class="w-100 h-auto" />
                                </div>
                                <div class="col-8">
                                    <div class="d-flex flex-column justify-content-around h-100">
                                        <div class="row gold text-nowrap flex-nowrap">
                                            <div class="col px-0">
                                                <i class="fas fa-crown fs-100"></i>
                                                <div class="d-inline fs-100">1位</div>
                                            </div>
                                            <div class="col-7 text-center px-0">
                                                <div class="fs-100">{{$result['datas'][0]->name}}</div>
                                            </div>
                                            <div class="col px-0">
                                                <div class="fs-100">{{$result['datas'][0]->count}}票</div>
                                            </div>
                                        </div>
                                        <div class="row silver text-nowrap flex-nowrap">
                                            <div class="col px-0">
                                                <i class="fas fa-crown fs-100"></i>
                                                <div class="d-inline fs-100">2位</div>
                                            </div>
                                            <div class="col-7 text-center px-0">
                                                <div class="fs-100">{{$result['datas'][1]->name}}</div>
                                            </div>
                                            <div class="col px-0">
                                                <div class="fs-100">{{$result['datas'][1]->count}}票</div>
                                            </div>
                                        </div>
                                        <div class="row brown text-nowrap flex-nowrap">
                                            <div class="col px-0">
                                                <i class="fas fa-crown fs-100"></i>
                                                <div class="d-inline fs-100">3位</div>
                                            </div>
                                            <div class="col-7 text-center px-0">
                                                <div class="fs-100">{{$result['datas'][2]->name}}</div>
                                            </div>
                                            <div class="col px-0">
                                                <div class="fs-100">{{$result['datas'][2]->count}}票</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </article>
    </main>
</body>

</html>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {!! Html::style('./css/bootstrap.min.css') !!}

    @yield('style')

    <title>陰陽師 onmyoji</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">陰陽師</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="page-scroll">
                    <a href="/admin/stage">關卡</a>
                </li>
                <li class="page-scroll">
                    <a href="/admin/monster">式神</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

{!! HTML::script("./js/jquery.js") !!}
{!! HTML::script("./js/bootstrap.min.js") !!}
@yield('script')

</body>
</html>

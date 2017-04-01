<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

	{!! Html::style('./css/bootstrap.min.css') !!}

	<style>
		.container {
			margin-top: 100px;
		}
	</style>

        <title>陰陽師 onmyoji</title>
    </head>
    <body>
	<div class="container">
		<div class="row">
			<div class="col-sm-7 col-sm-offset-2">
				<input class="form-control">
			</div>
			<div class="col-sm-1">
				<button id="query" class="btn btn-primary">查詢</button>
			</div>
		</div>
	</div>
    </body>

    {!! HTML::script("./js/bootstrap.min.js") !!}
    {!! HTML::script("./js/jquery.js") !!}

    <script>
        $('#query').click(function () {
            alert(1);
        });
    </script>
</html>

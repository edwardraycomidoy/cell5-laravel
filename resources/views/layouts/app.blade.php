<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{{ env('APP_NAME') }}</title>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

		<style>
			body {
				font-family: 'Nunito';
			}

			a {
				color: rgb(0, 0, 238);
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<header>
			<div class="container">
				<br>
				<a href="{{ route('members.index') }}">Members</a>
				&nbsp;
				&bull;
				&nbsp;
				<a href="{{ route('collections.index') }}">Collections</a>
				&nbsp;
				&bull;
				&nbsp;
				<a href="{{ route('spreadsheet') }}">Spreadsheet</a>
				<br>
				<br>
			</div>
		</header>

		<section>
			<div class="container">
				@yield('content')
			</div>
		</section>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{{ asset('js/script.js') }}"></script>
	</body>
</html>

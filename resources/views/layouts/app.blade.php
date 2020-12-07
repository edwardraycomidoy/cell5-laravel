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

			.nav-link {
				color: #fff !important;
			}
		</style>
	</head>
	<body>
		<header class="bg-primary mb-3">
			<div class="container">
				<nav class="navbar navbar-expand-lg navbar-dark px-0">
					<a class="navbar-brand" href="{{ route('home') }}">Home</a>

					<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#header-navbar">
						<span class="navbar-toggler-icon"></span>
					</button>

					<div class="collapse navbar-collapse" id="header-navbar">
						@auth
							<ul class="navbar-nav mr-auto">
								<li class="nav-item">
									<a class="nav-link" href="{{ route('members.index') }}">Members</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{ route('collections.index') }}">Collections</a>
								</li>
							</ul>
						@endauth

						<ul class="navbar-nav ml-auto">

							@guest
								<li class="nav-item">
									<a class="nav-link" href="{{ route('login') }}">Login</a>
								</li>
							@endguest

							@auth
								<li class="nav-item">
									<form action="{{ route('logout') }}" method="post">
										@csrf
									</form>
									<a class="nav-link" href="javascript:void(0);" onclick="$(this).prev().submit()">Logout</a>
								</li>
							@endauth

						</ul>
					</div>
				</nav>
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

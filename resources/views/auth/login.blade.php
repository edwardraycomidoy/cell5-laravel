@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-6 offset-lg-3">
		<h2 class="text-center">Login</h2>

		@if(session()->has('status'))
			<div class="alert alert-danger rounded-0 mt-3" role="alert">{{ session('status') }}</div>
		@endif

		<div class="card rounded-0 mt-3">
			<div class="card-body">
				<form action="{{ route('login') }}" method="post">
					@csrf
					<div class="form-group">
						<label for="email">Email {{ old('email') }}</label>
						<input type="email" class="form-control rounded-0" id="email" name="email" placeholder="Enter email" required @error('email') style="border-color:#dc3545" @enderror>

						@error('email')
							<small class="form-text text-danger">{{ $message }}</small>
						@enderror
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control rounded-0" id="password" name="password" placeholder="Enter password" required @error('password') style="border-color:#dc3545" @enderror>

						@error('password')
							<small class="form-text text-danger">{{ $message }}</small>
						@enderror
					</div>
					<div class="form-check">
						<input type="checkbox" class="form-check-input" id="remember" name="remember">
						<label class="form-check-label" for="remember">Remember me</label>
					</div>
					<button type="submit" class="btn btn-primary w-100 rounded-0 mt-3">Submit</button>
					<div class="text-center mt-3 d-none">
						<a href="{{ route('login') }}" title="Forgot password?">Forgot password?</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

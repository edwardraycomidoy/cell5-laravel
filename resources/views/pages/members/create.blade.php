@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">

		<h2>Create Member</h2>
			
		<a href="{{ route('members.index') }}">Back</a>

		<br>
		<br>

		<form action="{{ route('members.store') }}" method="post">
			@csrf
			<div>
				<label>First Name</label>
				<input
					type="text"
					name="first_name"
					value="{{ old('first_name') }}"
				>

				@error('first_name')
					<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<br>
			<div>
				<label>Middle Initial</label>
				<input
					type="text"
					name="middle_initial"
					value="{{ old('middle_initial') }}"
				>

				@error('middle_initial')
					<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<br>
			<div>
				<label>Last Name</label>
				<input
					type="text"
					name="last_name"
					value="{{ old('last_name') }}"
				>

				@error('last_name')
					<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<br>
			<div>
				<label>Suffix</label>
				<input
					type="text"
					name="suffix"
					value="{{ old('suffix') }}"
				>

				@error('suffix')
					<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<br>
			<div>
				<label>Joined on</label>
				<input
					type="text"
					name="joined_on"
					value="{{ old('joined_on') }}"
				>

				@error('joined_on')
					<div class="alert alert-danger">{{ $message }}</div>
				@enderror
			</div>
			<br>
			<button type="submit">Submit</button>
		</form>
	</div>
</div>

@endsection

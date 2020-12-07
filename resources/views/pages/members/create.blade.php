@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Add Member</h2>

		<a href="{{ route('members.index') }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<div class="card rounded-0 mt-3 w-50">
			<div class="card-body">

				<form action="{{ route('members.store') }}" method="post">
					@csrf
					<div class="form-group">
						<label>First Name</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="first_name"
							value="{{ old('first_name') }}"
							required
						>

						@error('first_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Middle Initial</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="middle_initial"
							value="{{ old('middle_initial') }}"
						>

						@error('middle_initial')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Last Name</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="last_name"
							value="{{ old('last_name') }}"
							required
						>

						@error('last_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Suffix</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="suffix"
							value="{{ old('suffix') }}"
						>

						@error('suffix')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Joined on</label>
						<input
							type="text"
							class="form-control rounded-0 datepicker"
							name="joined_on"
							value="{{ old('joined_on') }}"
							required
						>

						@error('joined_on')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<button type="submit" class="btn btn-primary w-100 rounded-0 mt-2">Submit</button>
				</form>

			</div>
		</div>

	</div>
</div>

@endsection

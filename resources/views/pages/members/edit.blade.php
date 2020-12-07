@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>{{ $member->first_name . ' ' . (!is_null($member->middle_initial) ? $member->middle_initial . '. ' : '') . $member->last_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') }}</h2>
		<h5 class="mb-3">Member</h5>

		<a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<div class="card rounded-0 mt-3 w-50">
			<div class="card-body">

				<form action="{{ route('members.update', $member) }}" method="post">
					@csrf
					@method('put')
					<div class="form-group">
						<label>First Name</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="first_name"
							value="{{ !is_null(old('first_name')) ? old('first_name') : $member->first_name }}"
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
							value="{{ !is_null(old('middle_initial')) ? old('middle_initial') : $member->middle_initial }}"
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
							value="{{ !is_null(old('last_name')) ? old('last_name') : $member->last_name }}"
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
							value="{{ !is_null(old('suffix')) ? old('suffix') : $member->suffix }}"
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
							value="{{ !is_null(old('joined_on')) ? old('joined_on') : $member->joined_on }}"
						>

						@error('joined_on')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<button type="submit" class="btn btn-primary w-100 rounded-0 mt-3">Submit</button>
				</form>

			</div>
		</div>
		
	</div>
</div>

@endsection

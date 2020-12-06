@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Create Collection</h2>
			
		<a href="{{ route('collections.index') }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<div class="card rounded-0 mt-3 w-50">
			<div class="card-body">

				<form action="{{ route('collections.store') }}" method="post">
					@csrf
					<div class="form-group">
						<label>Member Name</label>
						<select name="member_id" class="form-control rounded-0">
							<option value="">&nbsp;</option>
							@foreach($members as $member)
								<option value="{{ $member->id }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</option>
							@endforeach
						</select>

						@error('member_id')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group mb-0">
						<label>Claimant</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="claimant" id="claimant-member" value="0" checked>
						<label class="form-check-label" for="claimant-member">Member</label>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="radio" name="claimant" id="claimant-beneficiary" value="1">
						<label class="form-check-label" for="claimant-beneficiary">Beneficiary</label>
					</div>

					<div class="form-group">
						<label>First Name</label>
						<input type="text" class="form-control rounded-0" name="first_name" disabled>

						@error('first_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Middle Initial</label>
						<input type="text" class="form-control rounded-0" name="middle_initial" disabled>

						@error('middle_initial')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Last Name</label>
						<input type="text" class="form-control rounded-0" name="last_name" disabled>

						@error('last_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Suffix</label>
						<input type="text" class="form-control rounded-0" name="suffix" disabled>

						@error('suffix')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Due on</label>
						<input type="text" class="form-control rounded-0" name="due_on" value="{{ date('Y-m-d') }}">

						@error('due_on')
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

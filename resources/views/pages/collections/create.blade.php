@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h1>Create Collection</h1>
			
		<a href="{{ route('collections.index') }}">Back</a>

		<br>
		<br>

		<form action="{{ route('collections.store') }}" method="post">
			@csrf
			<div>
				<label>Member Name</label>
				<select name="member_id">
					<option value="">&nbsp;</option>
					@foreach($members as $member)
						<option value="{{ $member->id }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</option>
					@endforeach
				</select>
			</div>
			<br>
			<div>
				<label>Claimant</label>
				<label>
					<input type="radio" name="claimant" value="0" checked>
					Member
				</label>
				<label>
					<input type="radio" name="claimant" value="1">
					Beneficiary
				</label>
			</div>
			<br>
			<div>
				<label>First Initial</label>
				<input type="text" name="first_name" disabled>
			</div>
			<br>
			<div>
				<label>Middle Initial</label>
				<input type="text" name="middle_initial" disabled>
			</div>
			<br>
			<div>
				<label>Last Name</label>
				<input type="text" name="last_name" disabled>
			</div>
			<br>
			<div>
				<label>Suffix</label>
				<input type="text" name="suffix" disabled>
			</div>
			<br>
			<div>
				<label>Due on</label>
				<input type="text" name="due_on" value="{{ date('Y-m-d') }}">
			</div>
			<br>
			<button type="submit">Submit</button>
		</form>

	</div>
</div>

@endsection

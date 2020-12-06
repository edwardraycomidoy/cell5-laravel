@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>{{ $member->first_name . ' ' . (!is_null($member->middle_initial) ? $member->middle_initial . '. ' : '') . $member->last_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') }}</h2>
		<h3>Member</h3>

		<a href="{{ route('members.show', $member) }}">Back</a>

		<br>
		<br>

		<form action="{{ route('members.update', $member) }}" method="post">
			@csrf
			@method('put')
			<div>
				<label>First Name</label>
				<input type="text" name="first_name" value="{{ $member->first_name }}">
			</div>
			<br>
			<div>
				<label>Middle Initial</label>
				<input type="text" name="middle_initial" value="{{ $member->middle_initial }}">
			</div>
			<br>
			<div>
				<label>Last Name</label>
				<input type="text" name="last_name" value="{{ $member->last_name }}">
			</div>
			<br>
			<div>
				<label>Suffix</label>
				<input type="text" name="suffix" value="{{ $member->suffix }}">
			</div>
			<br>
			<div>
				<label>Joined on</label>
				<input type="text" name="joined_on" value="{{ $member->joined_on }}">
			</div>
			<br>
			<button type="submit">Submit</button>
		</form>
	</div>
</div>

@endsection

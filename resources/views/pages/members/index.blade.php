@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h1>Members</h1>

		<a href="{{ route('members.create') }}">Create</a>

		<ul>
			@foreach($members as $member)
				<li>
					<a href="{{ route('members.show', $member) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
				</li>
			@endforeach
		</ul>

		{{ $members->links() }}
	</div>
</div>

@endsection

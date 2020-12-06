@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Members</h2>

		<a href="{{ route('members.create') }}">Create</a>

		<form action="{{ route('members.search') }}" id="search-members-form">
			<input type="text" name="keywords" placeholder="Search keywords..." value="{{ $keywords }}" autocomplete="off">
		</form>

		<x-members-list :members="$members" />

	</div>
</div>

@endsection

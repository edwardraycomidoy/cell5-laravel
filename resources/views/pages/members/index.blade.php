@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h1>Members</h1>

		<a href="{{ route('members.create') }}">Create</a>

		<br>
		<br>
		<form action="{{ route('members.search') }}" id="search-members-form">
			<input type="text" name="keywords" placeholder="Search keywords..." value="{{ $keywords }}" autocomplete="off">
		</form>
		<br>

		<x-members-list :members="$members" />

	</div>
</div>

@endsection

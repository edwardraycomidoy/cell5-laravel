@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Members</h2>

		<a href="{{ route('members.create') }}" class="btn btn-sm btn-success rounded-0">Add</a>

		<form action="{{ route('members.search') }}" id="search-members-form" class="mt-3 w-50">
			<input type="text" class="form-control rounded-0" name="keywords" placeholder="Search keywords..." value="{{ $keywords }}" autocomplete="off">
		</form>

		<x-members-list :members="$members" :collections="$collections" :payments="$payments" />
	</div>
</div>

@endsection

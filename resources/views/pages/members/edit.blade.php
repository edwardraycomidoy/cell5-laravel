@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h2>{{ $member->first_name }} {{ (!is_null($member->middle_initial) ? $member->middle_initial . '. ' : '') . $member->last_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') }}</h2>
		<h5 class="mb-3">Member</h5>
		<a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-success rounded-0">Back</a>
	</div>
	<div class="col-lg-6">
		<x-add-member-form :mode="'edit'" :member="$member" />
	</div>
</div>

@endsection

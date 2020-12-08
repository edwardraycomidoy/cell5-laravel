@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h2>Add Member</h2>
		<a href="{{ route('members.index') }}" class="btn btn-sm btn-success rounded-0">Back</a>
	</div>
	<div class="col-lg-6">
		<x-add-member-form :mode="'add'" />
	</div>
</div>

@endsection

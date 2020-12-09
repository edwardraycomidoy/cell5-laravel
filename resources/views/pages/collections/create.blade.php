@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg-12">
		<h2>Add Collection</h2>
		<a href="{{ route('collections.index') }}" class="btn btn-sm btn-success rounded-0">Back</a>
	</div>
	<div class="col-lg-6">
		<x-add-collection-form :mode="'add'" :members="$members" />
	</div>
</div>

@endsection

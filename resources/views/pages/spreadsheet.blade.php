@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Spreadsheet View</h2>

		<x-spreadsheet :members="$members" :collections="$collections" :payments="$payments" />
	</div>
</div>

@endsection

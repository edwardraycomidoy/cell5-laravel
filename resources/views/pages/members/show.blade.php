@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>{{ $member->first_name . ' ' . (!is_null($member->middle_initial) ? $member->middle_initial . '. ' : '') . $member->last_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') }}</h2>
		<h5 class="mb-3">Member</h5>

		<a href="{{ $back_url }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-info rounded-0">Edit</a>

		<a href="javascript:void(0);" class="btn btn-sm btn-danger rounded-0" onclick="$('#delete-member').submit();">Delete</a>
		<form action="{{ route('members.destroy', $member) }}" method="post" id="delete-member">
			@csrf
			@method('delete')
		</form>

		@if(session()->has('type') && session()->has('message'))
			<div class="alert alert-{{ session('type') }} rounded-0 w-50 mt-3 mb-0" role="alert">{{ session('message') }}</div>
		@endif

		@if($collections->count() > 0)

			<br>
			<table class="table table-bordered table-striped table-sm w-auto">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Claimant</th>
						<th>Member</th>
						<th>Due on</th>
						<th>Paid</th>
					</tr>
				</thead>
				<tbody>
					@foreach($collections as $collection)
						<tr data-collection-id="{{ $collection->id }}">
							<td>
								<a href="{{ route('collections.show', $collection->id) }}">View</a>
							</td>
							<td>
								@if(!is_null($collection->claimant_id))
									{{ $collection->claimant_last_name . ', ' . $collection->claimant_first_name . (!is_null($collection->claimant_suffix) ? ' ' . $collection->claimant_suffix : '') . (!is_null($collection->claimant_middle_initial) ? ' ' . $collection->claimant_middle_initial . '.' : '') }}
								@else
									<a href="{{ route('members.show', $collection->member_id) }}">{{ $collection->member_last_name . ', ' . $collection->member_first_name . (!is_null($collection->member_suffix) ? ' ' . $collection->member_suffix : '') . (!is_null($collection->member_middle_initial) ? ' ' . $collection->member_middle_initial . '.' : '') }}</a>
								@endif
							</td>
							<td>
								@if(!is_null($collection->claimant_id))
									<a href="{{ route('members.show', $collection->member_id) }}">{{ $collection->member_last_name . ', ' . $collection->member_first_name . (!is_null($collection->member_suffix) ? ' ' . $collection->member_suffix : '') . (!is_null($collection->member_middle_initial) ? ' ' . $collection->member_middle_initial . '.' : '') }}</a>
								@else
									<em>Same as claimant</em>
								@endif
							</td>
							<td>{{ $collection->due_on }}</td>
							<td class="text-center">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-square mark-paid @if(!is_null($collection->payment_id))d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
									<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
								</svg>
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-square mark-unpaid @if(is_null($collection->payment_id))d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
									<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
									<path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
								</svg>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<form action="{{ route('payments.store') }}" method="post" id="mark-paid-form">
				@csrf
				<input type="hidden" name="member_id" value="{{ $member->id }}">
				<input type="hidden" name="collection_id">
			</form>

			<form method="post" id="mark-unpaid-form">
				@csrf
				@method('delete')
			</form>

		@endif
	</div>
</div>

@endsection

@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Collection</h2>

		<a href="{{ route('collections.index') }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<a href="{{ route('collections.edit', $collection->id) }}" class="btn btn-sm btn-info rounded-0">Edit</a>

		<a href="javascript:void(0);" id="delete-collection-a" class="btn btn-sm btn-danger rounded-0">Delete</a>
		<form action="{{ route('collections.destroy', $collection->id) }}" method="post" id="delete-collection-form">
			@csrf
			@method('delete')
		</form>

		@if(session()->has('type') && session()->has('message'))
			<div class="alert alert-{{ session('type') }} rounded-0 w-50 mt-3 mb-0" role="alert">{{ session('message') }}</div>
		@endif

		<table class="table table-bordered table-striped table-sm w-auto mt-3">
			<thead>
				<tr>
					<th>Member</th>
					<th>Claimant</th>
					<th>Due on</th>
					<!--
					<th>Released on</th>
					-->
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<a href="{{ route('members.show', $collection->member_id) }}">{{ $collection->member_last_name . ', ' . $collection->member_first_name . (!is_null($collection->member_suffix) ? ' ' . $collection->member_suffix : '') . (!is_null($collection->member_middle_initial) ? ' ' . $collection->member_middle_initial . '.' : '') }}</a>
					</td>
					<td>
						@if(!is_null($collection->claimant_id))
							{{ $collection->claimant_last_name . ', ' . $collection->claimant_first_name . (!is_null($collection->claimant_suffix) ? ' ' . $collection->claimant_suffix : '') . (!is_null($collection->claimant_middle_initial) ? ' ' . $collection->claimant_middle_initial . '.' : '') }}
						@else
							<em>Member</em>
						@endif
					</td>
					<td>{{ $collection->due_on }}</td>
					<!--
					<td>
						@if(!is_null($collection->released_on))
							{{ $collection->released_on }}
						@else
							&nbsp;
						@endif
					</td>
					-->
				</tr>
			</tbody>
		</table>

		<table class="table table-bordered table-striped table-sm w-auto mt-3">
			<thead>
				<tr>
					<th>Member</th>
					<th>Paid</th>
				</tr>
			</thead>
			<tbody>
				@if($members->count() > 0)
					@foreach($members as $member)
						<tr data-member-id="{{ $member->id }}">
							<td>
								<a href="{{ route('members.show', $member->id) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
							</td>
							<td class="text-center">
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-square mark-paid @if((bool)$member->paid)d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
									<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
								</svg>
								<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-square mark-unpaid @if(!(bool)$member->paid)d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
									<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
									<path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
								</svg>
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
		<form action="{{ route('payments.store') }}" method="post" id="mark-paid-form">
			@csrf
			<input type="hidden" name="member_id">
			<input type="hidden" name="collection_id" value="{{ $collection->id }}">
		</form>
		<form action="{{ route('payments.destroy') }}" method="post" id="mark-unpaid-form">
			@csrf
			@method('delete')
			<input type="hidden" name="member_id">
			<input type="hidden" name="collection_id" value="{{ $collection->id }}">
		</form>
		{{ $members->links() }}
	</div>
</div>

@endsection

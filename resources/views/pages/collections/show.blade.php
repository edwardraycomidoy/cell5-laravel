@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h1>Collection</h1>

		<a href="{{ route('collections.index') }}">Back</a>
		&nbsp;
		&bull;
		&nbsp;

		<a href="{{ route('collections.edit', $collection->id) }}">Edit</a>

		<br>
		<br>

		<table border="1" cellspacing="0" cellpadding="5">
			<thead>
				<tr>
					<th>Member</th>
					<th>Claimant</th>
					<th>Due on</th>
					<th>Released on</th>
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
					<td>
						@if(!is_null($collection->released_on))
							{{ $collection->released_on }}
						@else
							&nbsp;
						@endif
					</td>
				</tr>
			</tbody>
		</table>

		<br>

		<h2>Unpaid Members</h2>

		@if($unpaid_members->count() > 0)

			<table border="1" cellspacing="0" cellpadding="5">
				<thead>
					<tr>
						<th>Member</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($unpaid_members as $member)
						<tr>
							<td>
								<a href="{{ route('members.show', $member->id) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
							</td>
							<td>
								<a href="javascript:void(0);" class="mark-paid" data-member-id="{{ $member->id }}">Mark Paid</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<form action="{{ route('payments.store') }}" method="post" id="mark-paid-form">
				@csrf
				<input type="hidden" name="collection_id" value="{{ $collection->id }}">
				<input type="hidden" name="member_id">
			</form>

		@else

		@endif

		<br>

		<h2>Paid Members</h2>

		@if($paid_members->count() > 0)

			<table border="1" cellspacing="0" cellpadding="5">
				<thead>
					<tr>
						<th>Member</th>
						<th>&nbsp;</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($paid_members as $member)
						<tr>
							<td>
								<a href="{{ route('members.show', $member->id) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
							</td>
							<td>pd.</td>
							<td>
								<a href="javascript:void(0);" class="mark-unpaid" data-action="{{ route('payments.destroy', $member->payment_id) }}">Mark Unpaid</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<form method="post" id="mark-unpaid-form">
				@csrf
				@method('delete')
			</form>
		@else

		@endif
	</div>
</div>

@endsection

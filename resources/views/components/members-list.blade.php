<div id="members-list">

	@if($members->count() > 0)

		<table class="table table-bordered table-striped table-sm w-auto mt-3">
			<thead>
				<tr>
					<th class="text-center" rowspan="2">Member</th>
					<th class="text-center" @if($collections->count() > 1) colspan="{{$collections->count()}}" @endif>Due on</th>
				</tr>
				<tr>
					@foreach($collections as $collection)
						<th class="text-center" style="font-weight:normal;cursor:default;" title="Claimant, Member">{{ $collection->due_on }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody>
				@foreach($members as $member)
					<tr data-member-id="{{ $member->id }}">
						<td>
							<a href="{{ route('members.show', $member) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
						</td>

						@foreach($payments[$member->id] as $payment)

							@if($payment->due_on >= $member->joined_on)

								<td class="text-center" data-collection-id="{{ $payment->collection_id }}">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-square mark-paid @if(!is_null($payment->payment_id))d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
										<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
									</svg>

									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-square mark-unpaid @if(is_null($payment->payment_id))d-none @endif" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="cursor:pointer;">
										<path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
										<path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
									</svg>
								</td>

							@else

								<td class="text-center"></td>

							@endif

						@endforeach

					</tr>
				@endforeach
			</tbody>
		</table>

		{{ $members->links() }}

		<form action="{{ route('payments.store') }}" method="post" id="mark-paid-form">
			@csrf
			<input type="hidden" name="member_id">
			<input type="hidden" name="collection_id">
		</form>

	@endif

</div>

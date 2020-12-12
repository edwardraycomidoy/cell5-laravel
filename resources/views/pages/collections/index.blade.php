@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Collections</h2>

		<a href="{{ route('collections.create') }}" class="btn btn-sm btn-success rounded-0">Add</a>

		@if(session()->has('type') && session()->has('message'))
			<div class="alert alert-{{ session('type') }} rounded-0 w-50 mt-3 mb-0" role="alert">{{ session('message') }}</div>
		@endif

		@if($collections->count() > 0)

			<table class="table table-bordered table-striped table-sm w-auto mt-3">
				<thead>
					<tr>
						<th>Member</th>
						<th>Claimant</th>
						<th>Due on</th>
						<!--
						<th>Released on</th>
						-->
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($collections as $collection)
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
							<td>
								<a href="{{ route('collections.show', $collection->id) }}">View</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			{{ $collections->links() }}

		@endif

	</div>
</div>

@endsection

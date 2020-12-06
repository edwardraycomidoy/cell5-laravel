@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Edit Collection</h2>
			
		<a href="{{ route('collections.show', $collection->id) }}" class="btn btn-sm btn-success rounded-0">Back</a>

		<table class="table table-bordered table-striped table-sm w-auto mt-3">
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

		<div class="card rounded-0 mt-3 w-50">
			<div class="card-body">

				<form action="{{ route('collections.update', $collection->id) }}" method="post">
					@csrf
					@method('put')
					<div class="form-group">
						<label>Member Name</label>
						<select name="member_id" class="form-control rounded-0">
							<option value="">&nbsp;</option>
							@foreach($members as $member)
								<option value="{{ $member->id }}"{{ $member->id === $collection->member_id ? ' selected' : '' }}>{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</option>
							@endforeach
						</select>

						@error('member_id')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group mb-0">
						<label>Claimant</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="claimant" id="claimant-member" value="0"{{ is_null($collection->claimant_id) ? ' checked' : '' }}>
						<label class="form-check-label" for="claimant-member">Member</label>
					</div>
					<div class="form-check mb-3">
						<input class="form-check-input" type="radio" name="claimant" id="claimant-beneficiary" value="1"{{ !is_null($collection->claimant_id) ? ' checked' : '' }}>
						<label class="form-check-label" for="claimant-beneficiary">Beneficiary</label>
					</div>

					<div class="form-group">
						<label>First Name</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="first_name"
							value="{{ !is_null($collection->claimant_id) ? $collection->claimant_first_name : '' }}"
							{{ is_null($collection->claimant_id) ? 'disabled' : 'required' }}
						>

						@error('first_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Middle Initial</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="middle_initial"
							value="{{ !is_null($collection->claimant_id) ? $collection->claimant_middle_initial : '' }}"
							{{ is_null($collection->claimant_id) ? 'disabled' : '' }}
						>

						@error('middle_initial')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Last Name</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="last_name"
							value="{{ !is_null($collection->claimant_id) ? $collection->claimant_last_name : '' }}"
							{{ is_null($collection->claimant_id) ? 'disabled' : 'required' }}
						>

						@error('last_name')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Suffix</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="suffix"
							value="{{ !is_null($collection->claimant_id) ? $collection->claimant_suffix : '' }}"
							{{ is_null($collection->claimant_id) ? 'disabled' : '' }}
						>

						@error('suffix')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<div class="form-group">
						<label>Due on</label>
						<input
							type="text"
							class="form-control rounded-0"
							name="due_on"
							value="{{ $collection->due_on }}"
							required
						>

						@error('due_on')
							<div class="alert alert-sm alert-danger rounded-0 mt-2">{{ $message }}</div>
						@enderror
					</div>
					<button type="submit" class="btn btn-primary w-100 rounded-0 mt-3">Submit</button>
				</form>

			</div>
		</div>
	</div>
</div>

@endsection

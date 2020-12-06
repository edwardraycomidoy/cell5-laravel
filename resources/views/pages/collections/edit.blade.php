@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-lg">
		<h2>Edit Collection</h2>
			
		<a href="{{ route('collections.show', $collection->id) }}">Back</a>

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
		<br>

		<form action="{{ route('collections.update', $collection->id) }}" method="post">
			@csrf
			@method('put')
			<div>
				<label>Member Name</label>
				<select name="member_id">
					<option value="">&nbsp;</option>
					@foreach($members as $member)
						<option value="{{ $member->id }}"{{ $member->id === $collection->member_id ? ' selected' : '' }}>{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</option>
					@endforeach
				</select>
			</div>
			<br>
			<div>
				<label>Claimant</label>
				<label>
					<input type="radio" name="claimant" value="0"{{ is_null($collection->claimant_id) ? ' checked' : '' }}>
					Member
				</label>
				<label>
					<input type="radio" name="claimant" value="1"{{ !is_null($collection->claimant_id) ? ' checked' : '' }}>
					Beneficiary
				</label>
			</div>
			<br>
			<div>
				<label>First Name</label>
				<input type="text" name="first_name"{{ is_null($collection->claimant_id) ? ' disabled' : '' }} value="{{ !is_null($collection->claimant_id) ? $collection->claimant_first_name : '' }}">
			</div>
			<br>
			<div>
				<label>Middle Initial</label>
				<input type="text" name="middle_initial"{{ is_null($collection->claimant_id) ? ' disabled' : '' }} value="{{ !is_null($collection->claimant_id) ? $collection->claimant_middle_initial : '' }}">
			</div>
			<br>
			<div>
				<label>Last Name</label>
				<input type="text" name="last_name"{{ is_null($collection->claimant_id) ? ' disabled' : '' }} value="{{ !is_null($collection->claimant_id) ? $collection->claimant_last_name : '' }}">
			</div>
			<br>
			<div>
				<label>Suffix</label>
				<input type="text" name="suffix"{{ is_null($collection->claimant_id) ? ' disabled' : '' }} value="{{ !is_null($collection->claimant_id) ? $collection->claimant_suffix : '' }}">
			</div>
			<br>
			<div>
				<label>Due on</label>
				<input type="text" name="due_on" value="{{ $collection->due_on }}">
			</div>
			<br>
			<button type="submit">Submit</button>
		</form>
	</div>
</div>

@endsection

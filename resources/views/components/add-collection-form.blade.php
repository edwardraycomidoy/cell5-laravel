<div class="card rounded-0 mt-3 w-100">
	<div class="card-body">
		<form action="{{ $mode === 'edit' ? route('collections.update', $collection->id) : route('collections.store') }}" method="post">
			@csrf
			@if($mode === 'edit')
				@method('put')
			@endif
			<div class="form-group">
				<label>Member Name</label>
				<select name="member_id" class="form-control rounded-0">
					<option value="">&nbsp;</option>
					@foreach($members as $member)
						<option value="{{ $member->id }}" {{ isset($collection) && $member->id === $collection->member_id ? ' selected' : '' }}>{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</option>
					@endforeach
				</select>

				@error('member_id')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group mb-0">
				<label>Claimant</label>
			</div>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="claimant" id="claimant-member" value="0"{{ !isset($collection) || is_null($collection->claimant_id) ? ' checked' : '' }}>
				<label class="form-check-label" for="claimant-member">Member</label>
			</div>
			<div class="form-check mb-3">
				<input class="form-check-input" type="radio" name="claimant" id="claimant-beneficiary" value="1"{{ isset($collection) && !is_null($collection->claimant_id) ? ' checked' : '' }}>
				<label class="form-check-label" for="claimant-beneficiary">Beneficiary</label>
			</div>
			<div class="form-group">
				<label>First Name</label>
				<input
					type="text"
					class="form-control rounded-0"
					name="first_name"
					value="{{ isset($collection) && !is_null($collection->claimant_id) ? $collection->claimant_first_name : '' }}"
					{{ !isset($collection) || is_null($collection->claimant_id) ? 'disabled' : 'required' }}
				>
				@error('first_name')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group">
				<label>Middle Initial</label>
				<input
					type="text"
					class="form-control rounded-0"
					name="middle_initial"
					value="{{ isset($collection) && !is_null($collection->claimant_id) ? $collection->claimant_middle_initial : '' }}"
					{{ !isset($collection) || is_null($collection->claimant_id) ? 'disabled' : '' }}
				>
				@error('middle_initial')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group">
				<label>Last Name</label>
				<input
					type="text"
					class="form-control rounded-0"
					name="last_name"
					value="{{ isset($collection) && !is_null($collection->claimant_id) ? $collection->claimant_last_name : '' }}"
					{{ !isset($collection) || is_null($collection->claimant_id) ? 'disabled' : 'required' }}
				>
				@error('last_name')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group">
				<label>Suffix</label>
				<input
					type="text"
					class="form-control rounded-0"
					name="suffix"
					value="{{ isset($collection) && !is_null($collection->claimant_id) ? $collection->claimant_suffix : '' }}"
					{{ !isset($collection) || is_null($collection->claimant_id) ? 'disabled' : '' }}
				>
				@error('suffix')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group">
				<label>Due on</label>
				<input
					type="text"
					class="form-control rounded-0 datepicker"
					name="due_on"
					value="{{ isset($collection) ? $collection->due_on : date('Y-m-d') }}"
				>
				@error('due_on')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<button type="submit" class="btn btn-primary w-100 rounded-0 mt-3">Submit</button>
		</form>
	</div>
</div>

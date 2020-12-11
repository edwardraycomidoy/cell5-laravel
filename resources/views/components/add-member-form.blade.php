<div class="card rounded-0 mt-3 w-100">
	<div class="card-body">
		<form action="{{ $mode === 'edit' ? route('members.update', $member) : route('members.store') }}" method="post">
			@csrf
			@if($mode === 'edit')
				@method('put')
			@endif
			<div class="form-group">
				<label>First Name</label>
				<input
					type="text"
					class="form-control rounded-0"
					name="first_name"
					value="{{ !is_null(old('first_name')) ? old('first_name') : (isset($member) ? $member->first_name : '') }}"
					required
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
					value="{{ !is_null(old('middle_initial')) ? old('middle_initial') : (isset($member) ? $member->middle_initial : '') }}"
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
					value="{{ !is_null(old('last_name')) ? old('last_name') : (isset($member) ? $member->last_name : '') }}"
					required
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
					value="{{ !is_null(old('suffix')) ? old('suffix') : (isset($member) ? $member->suffix : '') }}"
				>
				@error('suffix')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<div class="form-group">
				<label>Joined on</label>
				<input
					type="date"
					class="form-control rounded-0"
					name="joined_on"
					value="{{ !is_null(old('joined_on')) ? old('joined_on') : (isset($member) ? $member->joined_on : '') }}"
					required
				>
				@error('joined_on')
					<small class="form-text text-danger">{{ $message }}</small>
				@enderror
			</div>
			<button type="submit" class="btn btn-primary w-100 rounded-0 mt-2">Submit</button>
		</form>
	</div>
</div>

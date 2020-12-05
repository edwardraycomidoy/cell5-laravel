<div id="members-list">
	<ul>
		@foreach($members as $member)
			<li>
				<a href="{{ route('members.show', $member) }}">{{ $member->last_name . ', ' . $member->first_name . (!is_null($member->suffix) ? ' ' . $member->suffix : '') . (!is_null($member->middle_initial) ? ' ' . $member->middle_initial . '.' : '') }}</a>
			</li>
		@endforeach
	</ul>

	{{ $members->links() }}
</div>
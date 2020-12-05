$(function() {
	$('input[type="radio"][name="claimant"]').on('change', function() {
		let value = parseInt($(this).val())

		let bool

		if(value === 0)
			bool = true

		else if(value === 1)
			bool = false

		if(typeof bool !== typeof undefined)
		{
			$('input[type="text"][name="first_name"]').prop('disabled', bool).val('')
			$('input[type="text"][name="middle_initial"]').prop('disabled', bool).val('')
			$('input[type="text"][name="last_name"]').prop('disabled', bool).val('')
			$('input[type="text"][name="suffix"]').prop('disabled', bool).val('')
		}
	});

	/*
	$('a.mark-paid').on('click', function(event)
	{
		event.preventDefault()
		let member_id = $.trim($(this).data('member-id'))
		$('#mark-paid-form').find('input[name="member_id"]').val(member_id)
		$('#mark-paid-form').submit()
	});

	$('a.mark-unpaid').on('click', function(event)
	{
		event.preventDefault()
		let action = $.trim($(this).data('action'))
		$('#mark-unpaid-form').attr('action', action).submit()
	});
	*/

	$('svg.mark-paid, svg.mark-unpaid').on('click', function() {
		if(!$(this).hasClass('mark-paid') && !$(this).hasClass('mark-unpaid'))
			return

		$(this).addClass('d-none')
		$(this).siblings('svg').removeClass('d-none')

		if($(this).hasClass('mark-paid'))
		{
			let collection_id = $(this).closest('td').data('collection-id')
			console.log('collection_id: ' + collection_id)
			if(typeof collection_id === typeof undefined)
			{
				collection_id = $(this).closest('tr').data('collection-id')
				console.log('collection_id: ' + collection_id)
			}

			let member_id = $(this).closest('td').data('member-id')
			console.log('member_id: ' + member_id)
			if(typeof member_id === typeof undefined)
			{
				member_id = $(this).closest('tr').data('member-id')
				console.log('member_id: ' + member_id)
			}

			if(typeof collection_id !== typeof undefined)
				$('#mark-paid-form').find('input[name="collection_id"]').val($.trim(collection_id))

			if(typeof member_id !== typeof undefined)
				$('#mark-paid-form').find('input[name="member_id"]').val($.trim(member_id))

			$('#mark-paid-form').submit()
		}
		else
		{
			//$(this).closest('tr').removeData('collection-id')
			//$('#mark-unpaid-form').attr('action')
			//$('#mark-unpaid-form').submit()
		}
	});

	$('#mark-paid-form, #mark-unpaid-form').on('submit', function(event) {
		event.preventDefault()
		$.post($(this).attr('action'), $(this).serialize(), function(data) {
			
		}, 'json')
	});







	$('#search-members-form input[name="keywords"]').on('input', function() {
		$.get($(this).closest('form').attr('action'), $(this).closest('form').serialize(), function(html) {
			$('#members-list').replaceWith(html);
		}, 'html')
	})















})

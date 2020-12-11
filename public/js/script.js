$(function() {
	$('input[type="radio"][name="claimant"]').on('change', function()
	{
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
	})

	$('svg.mark-paid, svg.mark-unpaid').on('click', function()
	{
		$(this).addClass('d-none')
		$(this).siblings('svg').removeClass('d-none')

		if($(this).hasClass('mark-paid'))
		{
			let collection_id = $(this).closest('td').data('collection-id')
			if(typeof collection_id === typeof undefined)
				collection_id = $(this).closest('tr').data('collection-id')

			let member_id = $(this).closest('td').data('member-id')
			if(typeof member_id === typeof undefined)
				member_id = $(this).closest('tr').data('member-id')

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
	})

	$('#mark-paid-form, #mark-unpaid-form').on('submit', function(event) {
		event.preventDefault()
		$.post($(this).attr('action'), $(this).serialize(), function(data) {}, 'json')
	})

	$('#search-members-form input[name="keywords"]').on('input', function() {
		const forme = $(this).closest('form')

		let new_keywords = $.trim($(this).val())

		let old_keywords = forme.data('keywords')

		if(typeof old_keywords !== typeof undefined)
		{
			old_keywords = $.trim(old_keywords)
			if(new_keywords === old_keywords)
				return
		}
		else if(new_keywords === '')
			return

		forme.data('keywords', new_keywords)

		$.get(forme.attr('action'), { keywords: new_keywords }, function(html) {
			$('#members-list').replaceWith(html);
		}, 'html')
	})
})

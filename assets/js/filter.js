jQuery(document).ready(function($) {
    // Existing: Load Sub Destinations
    $('#destinations').on('change', function() {
        var parent_id = $(this).val();
        $.post(hotelFilterAjax.ajaxurl, {
            action: 'load_sub_zones',
            parent_id: parent_id
        }, function(response) {
            $('#sub-destinations').html(response);
        });
    });

    // Existing: Live Search for Facilities (already there)

	// Load Holiday Types based on Sub Destination
	$('#sub-destinations').on('change', function() {
		var sub_id = $(this).val();
		if (sub_id) {
			$.post(hotelFilterAjax.ajaxurl, {
				action: 'load_holiday_types',
				sub_id: sub_id
			}, function(response) {
				$('#holiday-type').html(response);
				$('#holiday-type').prop('disabled', false);
			});
		} else {
			$('#holiday-type').html('<option value="">Select Holiday Type</option>');
			$('#holiday-type').prop('disabled', true);
		}

		// Disable Facilities and clear it
		$('#facilities-wrapper').addClass('disabled');
		$('#facilities-search').prop('disabled', true);
		$('.facilities-options').empty();
	});

	// Load Facilities based on Holiday Type + Destination + Sub Destination
	$('#holiday-type').on('change', function() {
		var holiday_id = $(this).val();
		var destination_id = $('#destinations').val();
		var subdestination_id = $('#sub-destinations').val();

		if (holiday_id) {
			$('#facilities-spinner').show(); // Show loading spinner

			$.post(hotelFilterAjax.ajaxurl, {
				action: 'load_facilities',
				holiday_id: holiday_id,
				destination_id: destination_id,
				subdestination_id: subdestination_id
			}, function(response) {
				$('#facilities-spinner').hide(); // Hide spinner
				$('#facilities-wrapper').removeClass('disabled');
				$('#facilities-search').prop('disabled', false);
				$('.facilities-options').html(response);
			});
		} else {
			$('#facilities-wrapper').addClass('disabled');
			$('#facilities-search').prop('disabled', true);
			$('.facilities-options').empty();
		}
	});

    // Facilities Dropdown toggle
    $('.fc-label').on('click', function(e) {
        e.stopPropagation();
        $(this).next('.facilities-dropdown').toggleClass('open');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.facilities-dropdown-wrapper').length) {
            $('.facilities-dropdown').removeClass('open');
        }
    });

    // Search inside Facilities
    $('#facilities-search').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.facilities-options .facility-option').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

$(document).ready(function() {

	$('#loader').fadeOut(function(){
		$('.toast').addClass('show transition');
		setTimeout(function() {
			$('.toast').removeClass('show');
		}, 3000);
	});

	$('.menu-dropdown-wrapper > a').on('click', function(){
		$(this).closest('.menu-dropdown-wrapper').find('.menu-dropdown').slideToggle();
	});

	$('#burger').on('click', function(){
		$('#content-overlay').fadeIn('fast', function(){
			$('body').addClass('menu-open');
		});
	});

	$(document).on('click', '#content-overlay', function(){
		$('body').removeClass('menu-open');
		$('#content-overlay').fadeOut('fast');
	});

	$('.user-info img, .user-info span').on('click', function(){
		$(this).closest('.user-info').find('ul').fadeToggle();
	});

    $('.datatable').each(function(){
    	var table = $(this);
    	var options = {
	    	aaSorting: [], // Disable auto sorting
	    	columnDefs: [{ targets: 0, orderable: false }], // Disable sorting for first column (Delete checkbox column)
	    	initComplete: function(settings, json) {
	    		$(this).addClass('table-responsive');
	    		$('.dt-button').addClass('btn btn-primary btn-sm');
	    		$('.dt-buttons').prependTo('.datatable-wrapper');
	    		$('.dt-buttons').addClass('text-center  text-md-left');
	    		if ($(this).closest('.card').find('.actions').children().length > 0) {
	    			$('.dt-buttons').addClass('absolute pt-0 pb-4 pt-md-4 pb-md-0');
				}
	    	}
	    };
	    if (!table.hasClass('no-export')) {
	    	options['dom'] = "Blfrtip";
	        options['buttons'] = [
	            "excelHtml5",
	            "pdfHtml5"
	        ];
	    }
	    table.DataTable(options);
    });

    $(document).on('change', '.file-wrapper input', function(e){
    	var filesNames = '';
    	for (var i = 0; i < e.target.files.length; i++) filesNames += e.target.files[i].name + ', ';
        filesNames = filesNames.slice(0, -2);
        if (filesNames) {
            $(this).closest('.file-wrapper').attr('data-text', filesNames);
            $(this).closest('.file-wrapper').removeClass('placeholder');
        }
        else {
            $(this).closest('.file-wrapper').attr('data-text', $(this).closest('.file-wrapper').attr('data-placeholder'));
            $(this).closest('.file-wrapper').addClass('placeholder');
        }
    });

	$('.datepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		changeMonth: true,
		changeYear: true,
	});

	$('.timepicker .buttons-wrapper span').on('click', function(){
		var btn = $(this);
		var btns_wrapper = btn.closest('.buttons-wrapper');
		var timepicker = btns_wrapper.closest('.timepicker');
		var inputs_wrapper = timepicker.find('.inputs-wrapper');
		var hour_input = inputs_wrapper.find('input:nth-child(1)');
		var min_input = inputs_wrapper.find('input:nth-child(2)');
		var period_input = inputs_wrapper.find('input:nth-child(3)');
		var value_input = inputs_wrapper.find('input[type="hidden"]');

		var i = $(this).index() + 1;
		if (i == 1) {
			if (btns_wrapper.hasClass('upper')) {
				next_hour = +hour_input.val() + 1;
				if (next_hour > 12) next_hour = 1;
			} else {
				next_hour = +hour_input.val() - 1;
				if (next_hour < 1) next_hour = 12;
			}
			if (next_hour.toString().length == 1) next_hour = "0" + next_hour;
			hour_input.val(next_hour);
		} else if (i == 2) {
			if (btns_wrapper.hasClass('upper')) {
				next_min = +min_input.val() + 1;
				if (next_min > 59) next_min = 0;
			} else {
				next_min = +min_input.val() - 1;
				if (next_min < 0) next_min = 59;
			}
			if (next_min.toString().length == 1) next_min = "0" + next_min;
			min_input.val(next_min);
		} else {
			period_input.val(period_input.val() == 'AM' ? 'PM' : 'AM');
		}

		value_input.val(hour_input.val() + ':' + min_input.val() + ' ' + period_input.val());
	});

	var timeout = 0;
	var interval = 0;
	$('.timepicker .buttons-wrapper span').on('mousedown', function() {
		var btn = $(this);
		timeout = setTimeout(function(){
			interval = setInterval(function() {
				btn.trigger('click');
			}, 50);
		}, 500);
	}).on('mouseup mouseleave', function() {
		clearTimeout(timeout);
		clearInterval(interval);
	});

	// $('.dataTables_length select').addClass('regular-select');
	$('select:not(.regular-select)').select2();

	quilljs_textarea('.quill', {
		modules: {
			toolbar: [
				[{ header: [1, 2, 3, 4, 5, false] }],
				['bold', 'italic', 'underline'],
				[{ 'list': 'ordered'}, { 'list': 'bullet' }],
				[{ 'align': [] }],
				['image'],
				['link'],
				['clean'],
				// [{ 'color': [] }, { 'background': [] }],
				// [{ 'font': [] }],
			]
		},
		theme: 'snow'
	});

    var idsToDelete = [];
	$(document).on('change', '.delete-checkbox input', function(){
        var input = $(this);

        if (input.is(':checked')) {
            idsToDelete.push(input.val());
        } else {
            var index = idsToDelete.indexOf(input.val());
            if (index > -1) {
                idsToDelete.splice(index, 1);
            }
        }
    });

	$('form.bulk-delete').on('submit', function(){

		var form = $(this);

        var ids = '';
        for (let index = 0; index < idsToDelete.length; index++) {
            ids += idsToDelete[index] + ',';
        }
		ids = ids.slice(0, -1);

        form.attr('action', form.attr('action') + '/' + ids);

		return true;
	});

	$('.remove-current-image').on('click', function(){
		if (!$(this).find('input').val()) {
			$(this).find('input').val('true');
			$(this).find('.btn').text('Undo');
			$(this).closest('.form-group').find('.img-wrapper').slideUp();
		} else {
			$(this).find('input').val('');
			$(this).find('.btn').text('Remove');
			$(this).closest('.form-group').find('.img-wrapper').slideDown();
		}
    });

	$('.remove-current-file').on('click', function(){
		if (!$(this).find('input').val()) {
			$(this).find('input').val('true');
			$(this).find('.btn').text('Undo');
			$(this).closest('.form-group').find('.file-link-wrapper').slideUp();
		} else {
			$(this).find('input').val('');
			$(this).find('.btn').text('Remove');
			$(this).closest('.form-group').find('.file-link-wrapper').slideDown();
		}
	});

	$('.admin-role-main-label').on('click', function(){
		var inputs = $(this).closest('.form-group').find('input');
		var checked = false;
		for (let input of inputs) {
			checked = input.checked;
			if (checked) break;
		}
		inputs.prop('checked', !checked);
	});

	$('[data-slug-origin]').each(function(){
		var slug_input = $(this);
		var origin_input_name = slug_input.data('slug-origin');
		var origin_input = $('input[name="' + origin_input_name + '"]');

		origin_input.on('keyup', function(){
			slug_input.val(origin_input.val().toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,''))
		});
	});

	$('.sortable').sortable({
		update: function(event, ui) {
			$('.sortable .sortable-row').each(function(i){
				$(this).find('[name*="ht_pos"]').val(i + 1);
			});
		},
	});

});

$(document).mouseup(function(e){
    // if the target of the click isn't the container nor a descendant of the container
    if (!$('.user-info').is(e.target) && $('.user-info').has(e.target).length === 0) {
        $('.user-info ul').hide();
    }
});

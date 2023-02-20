window.addEventListener("pageshow", function (event) {
    if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
        window.location.reload(); // Refresh page when browser navigates to your page through history traversal
    }
});

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#loader').fadeOut(function () {
        $('.toast:not(.error)').addClass('show transition');
        setTimeout(function () {
            $('.toast:not(.error)').removeClass('show');
        }, 3000);
    });

    $('.menu-dropdown-wrapper > a').on('click', function () {
        $(this).closest('.menu-dropdown-wrapper').find('.menu-dropdown').slideToggle();
    });

    $('#burger').on('click', function () {
        $('#content-overlay').fadeIn('fast', function () {
            $('body').addClass('menu-open');
        });
    });

    $(document).on('click', '#content-overlay', function () {
        $('body').removeClass('menu-open');
        $('#content-overlay').fadeOut('fast');
    });

    $('.user-info img, .user-info span').on('click', function () {
        $(this).closest('.user-info').find('ul').fadeToggle();
    });

    $('.datatable').each(function () {
        var table = $(this);
        var options = {
            aaSorting: [], // Disable auto sorting
            columnDefs: [{ targets: 0, orderable: false }], // Disable sorting for first column (Delete checkbox column)
            initComplete: function (settings, json) {
                var wrapper = table.closest('.dataTables_wrapper');
                table.wrap('<div class="table-responsive"></div>');
                wrapper.find('.dt-button').addClass('btn btn-primary btn-sm');
                wrapper.find('.dt-buttons').prependTo('.datatable-wrapper');
                wrapper.find('.dt-buttons').addClass('text-center  text-md-left');
                if (wrapper.closest('.card').find('.actions').children().length > 0) {
                    $('.dt-buttons').addClass('absolute pt-0 pb-4 pt-md-4 pb-md-0');
                }
                wrapper.find('.dataTables_length select').addClass('select2-width-auto');
                if ($(this).closest('.datatable-wrapper').hasClass('has-filters')) {
                    $('<label class="filter-wrapper float-right"><i class="fa fa-filter ml-3"></i></label>').insertBefore('#DataTables_Table_0_filter');
                    $('#DataTables_Table_0_filter').addClass('p-0');
                }
            },
            autoWidth: false
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

    $(document).on('change', '.file-wrapper input', function (e) {
        if ($(this).closest('.multiple-images-wrapper').length) return;

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

    $('.multiple-images-wrapper input').on('change', async function (e) {
        var input = $(this);
        var imagesPreview = input.closest('.multiple-images-wrapper').find('.images-preview .row');
        var wrapper = $(this).closest('.multiple-images-wrapper');

        // Show images
        imagesPreview.closest('.images-preview').show();

        // Show loader
        if (!$('#loader').hasClass('overlay')) $('#loader').addClass('overlay');
        $('#loader').fadeIn();

        var formData = new FormData();
        formData.append('_method', wrapper.data('method'));
        for (let i = 0; i < input[0].files.length; i++) {
            formData.append('images[]', input[0].files[i]);
        }

        $.ajax({
            type: 'post',
            url: wrapper.data('ajax-url'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (r) {
                $('#loader').fadeOut();
                input[0].value = '';
                for (let i = 0; i < r.length; i++) {
                    input.closest('.multiple-images-wrapper').find('.images-preview .row').append(
                        '<div class="col-auto">' +
                        '<input type="hidden" name="' + wrapper.data('input-name') + '" value="' + r[i].path + '">' +
                        '<img class="img-thumbnail" src="' + r[i].url + '">' +
                        '<div class="bg-danger text-white">' +
                        '<i class="fa fa-times" aria-hidden="true"></i>' +
                        '</div>' +
                        '</div>'
                    );
                }
            }
        });
    });

    $(document).on('click', '.multiple-images-wrapper .images-preview .bg-danger', function (e) {
        if (!confirm('Are you sure?')) return;

        var imageWrapper = $(this).closest('.col-auto');
        var multipleImagesWrapper = $(this).closest('.multiple-images-wrapper');

        imageWrapper.remove();
        if (multipleImagesWrapper.find('.images-preview > .row > div:visible').length == 0) multipleImagesWrapper.find('.images-preview').hide();
    });

    $('.images-sortable').sortable();

    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
    });

    $('.timepicker .buttons-wrapper span').on('click', function () {
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
            var next_hour;
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
            var next_min;
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
    $('.timepicker .buttons-wrapper span').on('mousedown', function () {
        var btn = $(this);
        timeout = setTimeout(function () {
            interval = setInterval(function () {
                btn.trigger('click');
            }, 50);
        }, 500);
    }).on('mouseup mouseleave', function () {
        clearTimeout(timeout);
        clearInterval(interval);
    });

    $('select:not(.regular-select)').each(function () {
        if ($(this).hasClass('select2-width-auto')) $(this).select2({ width: 'auto', templateResult: resultState });
        else $(this).select2({ width: '100%', templateResult: resultState });
    });

    $('.select-multiple-custom').on('change', function () {
        var select = $(this);
        var data = select.select2('data');

        if (data.length) {
            for (let i = 0; i < data.length; i++) {
                // Save selected option
                var value = data[i].id;
                var label = data[i].text;

                if (value && !select.closest('.select-multiple-custom-wrapper').find('.selected-options input[type="hidden"][value="' + value + '"]').length) {
                    // Display option
                    var optionHtml = '';
                    optionHtml += '<div class="selected-option py-1 d-flex align-items-center border-bottom sortable-row">';
                    optionHtml += '<p class="flex-grow-1 mb-0">' + label + '</p>';
                    optionHtml += '<i class="fa fa-remove text-danger"></i>';
                    optionHtml += '<input type="hidden" name="' + select.data('name') + '[]" value="' + value + '" class="selected-option-id">';
                    optionHtml += '<input type="hidden" name="ht_pos[' + select.data('name') + '][' + value + ']" value="">';
                    optionHtml += '</div>';
                    select.closest('.select-multiple-custom-wrapper').find('.selected-options').append(optionHtml);
                }
            }
        }
    });

    $(document).on('click', '.selected-option .fa-remove', function () {
        var selectedOptionDisplay = $(this).closest('.selected-option');
        var selectedOptionId = selectedOptionDisplay.find('.selected-option-id').val();
        var selectWrapper = $(this).closest('.select-multiple-custom-wrapper');
        var select = selectWrapper.find('select');
        var selectValues = select.val();

        var i = selectValues.indexOf(selectedOptionId);
        if (i > -1) {
            selectValues.splice(i, 1);
            select.val(selectValues).change();
        }

        console.log(selectedOptionDisplay)
        selectedOptionDisplay.remove();
    });

    $('.select-multiple-custom').on("select2:unselecting", function (e) {
        let unselected_value = e.params.args.data.id;
        $(this).closest('.select-multiple-custom-wrapper').find('.selected-options .selected-option-id[value="' + unselected_value + '"]').closest('.selected-option').remove();
    });

    $('[id^="ckeditor_"]').each(function () {
        var id = $(this).attr('id');
        var uploadUrl = $(this).attr('upload-url');

        var ckeditor = CKEDITOR.replace(this.id, {
            height: 400,
            extraPlugins: 'format,embed,autoembed,image,maximize,blockquote,justify,bidi' + (CKEditorColors ? ',colorbutton' : ''),
            embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
            format_tags: 'p;h1;h2;h3;h4;h5;h6',
            colorButton_colors: CKEditorColors,
            colorButton_enableAutomatic: false,
            removeButtons: 'Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Styles',
            filebrowserUploadUrl: uploadUrl,
            on: {
                change: function (e) {
                    var cont = e.editor.getData();
                    cont = cont.replace(/<[^>]*>/g, ' ');
                    cont = cont.replace(/\s+/g, ' ');
                    cont = cont.replace(/\&nbsp;/g, ' ');
                    cont = cont.trim();
                    var n = cont.trim().split(' ').filter(function (s) { return s != ' ' && s != '' }).length;
                    this.element.$.closest('.word-count-wrapper').querySelector('.word-count-number').innerHTML = n;
                },
                instanceReady: function (e) {
                    this.fire('change');
                }
            }
        });
    });

    var idsToDelete = [];
    $(document).on('change', '.delete-checkbox input', function () {
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

    $('form.bulk-delete').on('submit', function () {

        var form = $(this);

        var ids = '';
        for (let index = 0; index < idsToDelete.length; index++) {
            ids += idsToDelete[index] + ',';
        }
        ids = ids.slice(0, -1);

        form.attr('action', form.attr('action') + '/' + ids);

        return true;
    });

    $('.remove-current-image').on('click', function () {
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

    $('.remove-current-file').on('click', function () {
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

    $('.admin-role-main-label').on('click', function () {
        var inputs = $(this).closest('.form-group').find('input');
        var checked = false;
        for (let input of inputs) {
            checked = input.checked;
            if (checked) break;
        }
        inputs.prop('checked', !checked);
    });

    $('.sortable .sortable-row').each(function (i) {
        $(this).find('[name*="ht_pos"]').val(i + 1);
    });

    $('.sortable').sortable({
        update: function (event, ui) {
            $('.sortable .sortable-row').each(function (i) {
                $(this).find('[name*="ht_pos"]').val(i + 1);
            });
        },
    });

    $(document).on('click', '.toast .fa-times', function () {
        var toast = $(this).closest('.toast');
        toast.removeClass('show');
        setTimeout(function () {
            toast.find('ul').html('');
        }, 1000);
    });

    $('.ht-preview-mode').on('click', function () {
        var form = $(this).closest('form');
        form.find('input[name="ht_preview_mode"]').val(1);
        form.submit();
        form.find('input[name="ht_preview_mode"]').val(0);
    });

    $('form[ajax]').on('submit', function (e) {
        e.preventDefault();

        if (!$('#loader').hasClass('overlay')) $('#loader').addClass('overlay');
        $('#loader').fadeIn();

        // Update ckeditor
        for (var instanceName in CKEDITOR.instances) CKEDITOR.instances[instanceName].updateElement();

        var form = $(this);
        var formData = new FormData($(this)[0]);

        $('.toast.error').removeClass('show');

        $.ajax({
            type: 'post',
            url: form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            success: function (r) {
                $('#loader').removeClass('overlay');
                window.location.href = r;
            },
            error: function (r) {
                $('#loader').fadeOut();
                var ul = '';

                if (r.status == 422) {
                    for (let key in r.responseJSON.errors) {
                        for (let i = 0; i < r.responseJSON.errors[key].length; i++) {
                            ul += '<li>' + r.responseJSON.errors[key][i] + '</li>';
                        }
                    }
                } else if (r.status == 303) {
                    window.open(r.responseJSON);
                } else {
                    ul += '<li>' + r.responseJSON.message + '</li>';
                }

                if (ul) {
                    $('.toast.error ul').html(ul);
                    $('.toast.error').addClass('show');
                }
            }
        });

    });

    $('.filter-wrapper i').on('click', function () {
        $('.filter-popup').fadeIn();
    });

    $(document).on('click', '.filter-popup', function (e) {
        $('.filter-popup').fadeOut();
    });

    $(document).on('click', '.filter-popup .card', function (e) {
        e.stopPropagation();
    });

    $(document).on('click', '.close-popup', function (e) {
        $(this).closest('.popup').fadeOut();
    });

    $('.server-showing-number-wrapper select').on('change', function () {
        $(this).closest('form').submit();
    });

    $('[onkeyup="wordCount(this)"]').keyup();

    $('.form-buttons-wrapper').each(function () {
        var html = $(this).html();
        $(this).parent().append('<div class="fixed-top form-buttons-wrapper-fixed px-2 px-sm-5"><div class="bg-white text-right shadow-sm py-3 px-4">' + html + '</div></div>');
    });

    $('[data-slug-origin]').each(function () {
        var slug_input = $(this);
        var origin_input_name = slug_input.data('slug-origin');
        var origin_input = $('input[name="' + origin_input_name + '"]');

        origin_input.on('keyup', function () {
            slug_input.val(origin_input.val().toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\u0621-\u064A0-9-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, ''))
        });
    });

    // START SELECT ALL FOR MULTIPLE SELECT
    $('.select-multiple-checkbox').each(function () {
        var check = $(this);
        var nearest_select = $(this).parent().parent().find('.select-multiple-custom-wrapper .select-multiple-custom option');
        var nearest_select_selected = $(this).parent().parent().find('.select-multiple-custom-wrapper .select-multiple-custom option:selected');
        if (nearest_select.length == nearest_select_selected.length) {
            check.prop("checked", true);
        }
    });

    $('.select-multiple-custom').on('change', function () {
        var check = $(this).parent().parent().find('.select-checkbox-container .select-multiple-checkbox');
        var nearest_select = check.parent().parent().find('.select-multiple-custom-wrapper .select-multiple-custom option');
        var nearest_select_selected = check.parent().parent().find('.select-multiple-custom-wrapper .select-multiple-custom option:selected');
        if (nearest_select.length == nearest_select_selected.length) {
            check.prop("checked", true);
        } else {
            check.prop("checked", false);
        }
    });

    $('.select-multiple-checkbox').on('click', function () {
        var check = $(this);
        var nearest_select = check.parent().parent().find('.select-multiple-custom-wrapper .select-multiple-custom');
        var data = nearest_select.select2('data');
        var options = nearest_select.find('option');
        var nearest_select_options = check.parent().parent().find('.select-multiple-custom-wrapper .selected-options');
        if (check.prop('checked') === true) {
            if (options.length) {
                options.each(function () {
                    $(this).prop("selected", true);
                });
                nearest_select.trigger("change");
            }
        } else {
            if (options.length) {
                options.each(function () {
                    $(this).prop("selected", false);
                });
                nearest_select_options.empty();
                nearest_select.trigger("change");
            }
        }
    });
    // END SELECT ALL FOR MULTIPLE SELECT

    // START SELECT ALL FOR DATATABLES
    $('.check-all input').on('click',function () {
        if($(this).is(':checked')){
            $('.delete-checkbox input').each(function( index ) {
                var input = $(this);
                input.prop( "checked", false );
                input.change();
            });
            $('.delete-checkbox input').each(function( index ) {
                var input = $(this);
                input.prop( "checked", true );
                input.change();
            });
        }else{
            $('.delete-checkbox input').each(function( index ) {
                var input = $(this);
                input.prop( "checked", false );
                input.change();
            });
        }
    });
    // END SELECT ALL FOR DATATABLES
});

$(document).mouseup(function (e) {
    // if the target of the click isn't the container nor a descendant of the container
    if (!$('.user-info').is(e.target) && $('.user-info').has(e.target).length === 0) {
        $('.user-info ul').hide();
    }
});

$(window).on('scroll', function () {
    if ($('.form-buttons-wrapper-fixed').length) {
        if ($(document).scrollTop() > 300) {
            $('.form-buttons-wrapper-fixed').addClass('show');
        } else {
            $('.form-buttons-wrapper-fixed').removeClass('show');
        }
    }
});

function readImageSrc(file) {
    return new Promise(function (resolve, reject) {
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (e) {
            resolve(e.target.result);
        }
    });
}

function wordCount(el) {
    var value = el.value;
    var span = el.closest('.word-count-wrapper').querySelector('.word-count-number');
    var wordCount = value == '' ? 0 : value.trim().split(' ').filter(function (s) { return s != ' ' && s != '' }).length;
    if (span) span.innerHTML = wordCount;
}

function resultState(data, container) {
    // console.log(data, container)
    if (data.element) {
    }
    return data.text;
}

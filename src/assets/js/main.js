$(document).ready(function () {

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

    var dt = new DataTransfer();
    $('.multiple-images-wrapper input').on('change', async function (e) {
        var input = $(this);
        var files = this.files;
        var imagesPreview = input.closest('.multiple-images-wrapper').find('.images-preview .row');

        // Show images
        imagesPreview.closest('.images-preview').show();

        if (input.closest('.multiple-images-wrapper').hasClass('edit')) {

            if (!$('#loader').hasClass('overlay')) $('#loader').addClass('overlay');
            $('#loader').fadeIn();

            var formData = new FormData();
            formData.append('_method', 'put');
            formData.append('name', input.attr('name').split('[]').join(''));
            for (let i = 0; i < input[0].files.length; i++) {
                formData.append('images[]', input[0].files[i]);

            }

            $.ajax({
                type: 'post',
                url: window.location.href + '/images',
                data: formData,
                processData: false,
                contentType: false,
                success: function (r) {
                    $('#loader').fadeOut();
                    input[0].value = '';
                    input.closest('.multiple-images-wrapper').find('.images-preview .row').html('');
                    for (let i = 0; i < r.length; i++) {
                        input.closest('.multiple-images-wrapper').find('.images-preview .row').append(
                            '<div class="col-auto saved" path="' + r[i].path + '">' +
                            '<img class="img-thumbnail" src="' + r[i].url + '">' +
                            '<div class="bg-danger text-white">' +
                            '<i class="fa fa-times" aria-hidden="true"></i>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            });

        } else {

            for (var i = 0; i < files.length; i++) {
                // Add files to DataTransfer instance
                dt.items.add(files[i]);

                // Read image and append it
                var imageSource = await readImageSrc(files[i]);
                imagesPreview.append(
                    '<div class="col-auto" dt-index="' + (dt.files.length - 1) + '">' +
                    '<img class="img-thumbnail" src="' + imageSource + '">' +
                    '<div class="bg-danger text-white">' +
                    '<i class="fa fa-times" aria-hidden="true"></i>' +
                    '</div>' +
                    '</div>'
                );
            }

        }

        // Override input files to the files in the DataTransfer instance
        this.files = dt.files;
    });

    $(document).on('click', '.multiple-images-wrapper .images-preview .bg-danger', function (e) {
        if (!confirm('Are you sure?')) return;

        var imageWrapper = $(this).closest('.col-auto');
        var multipleImagesWrapper = $(this).closest('.multiple-images-wrapper');
        var input = multipleImagesWrapper.find('input');
        var index = imageWrapper.index();

        imageWrapper.hide();
        if (multipleImagesWrapper.find('.images-preview > .row > div:visible').length == 0) multipleImagesWrapper.find('.images-preview').hide();
        if (imageWrapper.hasClass('saved')) {
            var imageWrapper = $(this).closest('.col-auto');
            var path = imageWrapper.attr('path');

            $.ajax({
                type: 'post',
                url: window.location.href + '/images/remove',
                data: {
                    _method: 'put',
                    path: path,
                    name: input.attr('name').split('[]').join('')
                },
                success: function (r) {
                    multipleImagesWrapper.find('.images-preview [path="' + path + '"]').remove();
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
                    } else {
                        ul += '<li>Server Error</li>';
                    }
                    $('.toast.error ul').html(ul);
                    $('.toast.error').addClass('show');

                    multipleImagesWrapper.find('.images-preview').show();
                    multipleImagesWrapper.find('.images-preview [path="' + path + '"]').show();
                }
            });
        } else {
            dt.items.remove(index);
            input[0].files = dt.files;
        }
    });

    $('.images-sortable').sortable({
        update: function (event, ui) {
            if ($(this).closest('.multiple-images-wrapper').hasClass('edit')) {

                var images = [];
                var input = $(this).closest('.multiple-images-wrapper').find('input');
                $(this).find('.col-auto').each(function () {
                    images.push($(this).attr('path'));
                });
                $.ajax({
                    type: 'post',
                    url: window.location.href + '/images/order',
                    data: {
                        _method: 'put',
                        images: images,
                        name: input.attr('name').split('[]').join('')
                    }
                });

            }
            else {

                var newDt = new DataTransfer();
                $(this).find('.col-auto').each(function () {
                    newDt.items.add(dt.files[$(this).attr('dt-index')]);
                });
                dt = newDt;
                $(this).closest('.multiple-images-wrapper').find('input')[0].files = dt.files;

            }
        },
    });

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

    // $('.dataTables_length select').addClass('regular-select');
    $('select:not(.regular-select)').select2();

    quilljs_textarea('.quill', {
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, 4, 5, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
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

    $('[data-slug-origin]').each(function () {
        var slug_input = $(this);
        var origin_input_name = slug_input.data('slug-origin');
        var origin_input = $('input[name="' + origin_input_name + '"]');

        origin_input.on('keyup', function () {
            slug_input.val(origin_input.val().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''))
        });
    });

    $('.sortable').sortable({
        update: function (event, ui) {
            $('.sortable .sortable-row').each(function (i) {
                $(this).find('[name*="ht_pos"]').val(i + 1);
            });
        },
    });

});

$(document).mouseup(function (e) {
    // if the target of the click isn't the container nor a descendant of the container
    if (!$('.user-info').is(e.target) && $('.user-info').has(e.target).length === 0) {
        $('.user-info ul').hide();
    }
});

"use strict";

window.addEventListener("pageshow", function (event) {
  if (event.persisted || typeof window.performance != "undefined" && window.performance.navigation.type === 2) {
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
      aaSorting: [],
      // Disable auto sorting
      columnDefs: [{
        targets: 0,
        orderable: false
      }],
      // Disable sorting for first column (Delete checkbox column)
      initComplete: function initComplete(settings, json) {
        $(this).wrap('<div class="table-responsive"></div>');
        $('.dt-button').addClass('btn btn-primary btn-sm');
        $('.dt-buttons').prependTo('.datatable-wrapper');
        $('.dt-buttons').addClass('text-center  text-md-left');

        if ($(this).closest('.card').find('.actions').children().length > 0) {
          $('.dt-buttons').addClass('absolute pt-0 pb-4 pt-md-4 pb-md-0');
        }

        $('select[name="DataTables_Table_0_length"]').addClass('select2-width-auto');

        if ($(this).closest('.datatable-wrapper').hasClass('has-filters')) {
          $('<label class="filter-wrapper float-right"><i class="fa fa-filter ml-3"></i></label>').insertBefore('#DataTables_Table_0_filter');
          $('#DataTables_Table_0_filter').addClass('p-0');
        }
      },
      autoWidth: false
    };

    if (!table.hasClass('no-export')) {
      options['dom'] = "Blfrtip";
      options['buttons'] = ["excelHtml5", "pdfHtml5"];
    }

    table.DataTable(options);
  });
  $(document).on('change', '.file-wrapper input', function (e) {
    if ($(this).closest('.multiple-images-wrapper').length) return;
    var filesNames = '';

    for (var i = 0; i < e.target.files.length; i++) {
      filesNames += e.target.files[i].name + ', ';
    }

    filesNames = filesNames.slice(0, -2);

    if (filesNames) {
      $(this).closest('.file-wrapper').attr('data-text', filesNames);
      $(this).closest('.file-wrapper').removeClass('placeholder');
    } else {
      $(this).closest('.file-wrapper').attr('data-text', $(this).closest('.file-wrapper').attr('data-placeholder'));
      $(this).closest('.file-wrapper').addClass('placeholder');
    }
  });
  $('.multiple-images-wrapper input').on('change', function _callee(e) {
    var input, imagesPreview, wrapper, formData, i;
    return regeneratorRuntime.async(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            input = $(this);
            imagesPreview = input.closest('.multiple-images-wrapper').find('.images-preview .row');
            wrapper = $(this).closest('.multiple-images-wrapper'); // Show images

            imagesPreview.closest('.images-preview').show(); // Show loader

            if (!$('#loader').hasClass('overlay')) $('#loader').addClass('overlay');
            $('#loader').fadeIn();
            formData = new FormData();
            formData.append('_method', wrapper.data('method'));

            for (i = 0; i < input[0].files.length; i++) {
              formData.append('images[]', input[0].files[i]);
            }

            $.ajax({
              type: 'post',
              url: wrapper.data('ajax-url'),
              data: formData,
              processData: false,
              contentType: false,
              success: function success(r) {
                $('#loader').fadeOut();
                input[0].value = '';

                for (var _i = 0; _i < r.length; _i++) {
                  input.closest('.multiple-images-wrapper').find('.images-preview .row').append('<div class="col-auto">' + '<input type="hidden" name="' + wrapper.data('input-name') + '" value="' + r[_i].path + '">' + '<img class="img-thumbnail" src="' + r[_i].url + '">' + '<div class="bg-danger text-white">' + '<i class="fa fa-times" aria-hidden="true"></i>' + '</div>' + '</div>');
                }
              }
            });

          case 10:
          case "end":
            return _context.stop();
        }
      }
    }, null, this);
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
    changeYear: true
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
  }); // $('.dataTables_length select').addClass('regular-select');

  $('select:not(.regular-select)').each(function () {
    if ($(this).hasClass('select2-width-auto')) $(this).select2({
      width: 'auto'
    });else $(this).select2({
      width: '100%'
    });
  });
  $('[id^="ckeditor_"]').each(function () {
    var id = $(this).attr('id');
    var uploadUrl = $(this).attr('upload-url');
    CKEDITOR.replace(this.id, {
      height: 400,
      extraPlugins: 'format,embed,autoembed,image,maximize,blockquote,justify' + (CKEditorColors ? ',colorbutton' : ''),
      embed_provider: '//ckeditor.iframe.ly/api/oembed?url={url}&callback={callback}',
      format_tags: 'p;h1;h2;h3;h4;h5;h6',
      colorButton_colors: CKEditorColors,
      colorButton_enableAutomatic: false,
      removeButtons: 'Cut,Copy,Paste,PasteText,PasteFromWord,Undo,Redo,Styles',
      filebrowserUploadUrl: uploadUrl
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

    for (var index = 0; index < idsToDelete.length; index++) {
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
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
      for (var _iterator = inputs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
        var input = _step.value;
        checked = input.checked;
        if (checked) break;
      }
    } catch (err) {
      _didIteratorError = true;
      _iteratorError = err;
    } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator["return"] != null) {
          _iterator["return"]();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
    }

    inputs.prop('checked', !checked);
  });
  $('[data-slug-origin]').each(function () {
    var slug_input = $(this);
    var origin_input_name = slug_input.data('slug-origin');
    var origin_input = $('input[name="' + origin_input_name + '"]');
    origin_input.on('keyup', function () {
      slug_input.val(origin_input.val().toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''));
    });
  });
  $('.sortable').sortable({
    update: function update(event, ui) {
      $('.sortable .sortable-row').each(function (i) {
        $(this).find('[name*="ht_pos"]').val(i + 1);
      });
    }
  });
  $(document).on('click', '.toast .fa-times', function () {
    var toast = $(this).closest('.toast');
    toast.removeClass('show');
    setTimeout(function () {
      toast.find('ul').html('');
    }, 1000);
  });
  $('form[ajax]').on('submit', function (e) {
    e.preventDefault();
    if (!$('#loader').hasClass('overlay')) $('#loader').addClass('overlay');
    $('#loader').fadeIn(); // Update ckeditor

    for (var instanceName in CKEDITOR.instances) {
      CKEDITOR.instances[instanceName].updateElement();
    }

    var form = $(this);
    var formData = new FormData($(this)[0]);
    $('.toast.error').removeClass('show');
    $.ajax({
      type: 'post',
      url: form.attr('action'),
      data: formData,
      processData: false,
      contentType: false,
      success: function success(r) {
        $('#loader').removeClass('overlay');
        window.location.href = r;
      },
      error: function error(r) {
        $('#loader').fadeOut();
        var ul = '';

        if (r.status == 422) {
          for (var key in r.responseJSON.errors) {
            for (var i = 0; i < r.responseJSON.errors[key].length; i++) {
              ul += '<li>' + r.responseJSON.errors[key][i] + '</li>';
            }
          }
        } else {
          ul += '<li>' + r.responseJSON.message + '</li>';
        }

        $('.toast.error ul').html(ul);
        $('.toast.error').addClass('show');
      }
    });
  });
  $('.filter-wrapper i').on('click', function () {
    $('.filter-popup').fadeIn();
  });
  $(document).on('click', '.filter-popup', function (e) {
    if (!e.target.closest('.card') || e.target.classList.contains('close-popup')) $('.filter-popup').fadeOut();
  });
  $('.server-showing-number-wrapper select').on('change', function () {
    $(this).closest('form').submit();
  });
});
$(document).mouseup(function (e) {
  // if the target of the click isn't the container nor a descendant of the container
  if (!$('.user-info').is(e.target) && $('.user-info').has(e.target).length === 0) {
    $('.user-info ul').hide();
  }
});

function readImageSrc(file) {
  return new Promise(function (resolve, reject) {
    var reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function (e) {
      resolve(e.target.result);
    };
  });
}
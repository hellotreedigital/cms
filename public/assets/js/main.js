$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
//Disable Image Draging
window.ondragstart = function() { return false; }

$(window).on("load", function () {

	$('.loadingScreen').fadeOut(500);
	pictureWrapper();
});

$(window).scroll(function() {
	var scroll = $(window).scrollTop();
	if (scroll > 0) {
		$(".navbar").addClass("onScroll")
	} else {
		$(".navbar").removeClass("onScroll")
	}

	if($(".about-grid").length > 0 && scroll > $(".about-grid").offset().top - 300){
		count();
	}

	if($(".map-section").length > 0 && scroll > $(".map-section").offset().top - 300){
		$(".map-section svg").addClass("draw");
	}
});

$(document).ready(function(){

	/*
	|--------------------------------------------------------------------------
	| Mobile Functions
	|--------------------------------------------------------------------------
	*/

	// Open filters on Mobile
	$(".filters ul li:first-child").click(function(){
		$(this).siblings().slideToggle();
	});

	// Burger styling
	$(".navbar-toggler").click(function(){
		if($(".navbar-collapse").is(":visible")){
			$(this).attr("aria-expanded", false);
		} else {
			$(this).attr("aria-expanded", true);
		}
		$(".navbar-collapse").slideToggle();
	});

	// news Slick
	$(".news-slick").each(function () {
		$(this).slick({
			arrows: false,
			fade: false,
			dots: true,
			autoplay: true,
			autoplaySpeed: 5000,
			pauseOnHover: false
		});
	});

	/*
	|--------------------------------------------------------------------------
	| End Mobile Functions
	|--------------------------------------------------------------------------
	*/

	// Navbar Dropdown
	$(".nav-item.dropdown").click(function(){
		$(this).find(".dropdown-menu").slideToggle();
	});

	// Header Slick
	$(".slick-container").each(function () {
		$(this).slick({
			arrows: true,
			fade: false,
			autoplay: true,
			autoplaySpeed: 5000,
			pauseOnHover: false
		});
	});

	// Share buttons fucntionality
	$(".share-wrapper").click(function(){
		$(this).toggleClass("show");
	});

	// Chaitpersons members slick
	$(".slick-chairspersons").each(function () {
		$(this).slick({
			arrows: true,
			autoplay: true,
			autoplaySpeed: 5000,
			slidesToShow: 3,
			slidesToScroll: 3,
			responsive: [
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					infinite: true
				}
			},
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					infinite: true
				}
			},
			]
		});
	});

	// Home Latest News
	$(".slider-for").each(function () {
		$(this).slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.slider-nav'
		});
		$('.slider-nav').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			dots: false,
			arrows: true,
			focusOnSelect: true,
			fade: true,
			asNavFor: '.slider-for'
		});
	});

	// Print Activities
	$(".btn-print").click(function(){
		window.print();
	});

	// Textarea Characters
	var maxLength = $(".form-characters").attr("maxlength");
	$(".form-characters").keyup(function() {
		var length = $(this).val().length;
		var length = maxLength-length;
		$('.text-characters span').text(length);
	});

	// Data slider
	$("#slider-range").each(function () {
		var min_year = $(this).data('min');
		var max_year = $(this).data('max');
		var from = $(this).data('from');
		var to = $(this).data('to');

		$(this).slider({
			range: true,
			min: min_year,
			max: max_year,
			values: [ from, to ],
			slide: function( event, ui ) {
				$("#date_from").val(ui.values[0]);
				$("#date_to").val(ui.values[1]);
				$( "#year" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
		});
		$("#date_from").val($(this).slider("values", 0));
		$("#date_to").val($(this).slider("values", 1));
		$( "#year" ).val( $(this).slider( "values", 0 ) + " - " + $(this).slider( "values", 1 ) );
	});

	// Chairpersons Member switch information
	$(".chairsperson-card").click(function(){
		$(".chairsperson-card").removeClass("active");
		$(this).addClass("active");
		var image = $(this).find("img").attr("src");
		var name = $(this).find("[data-name]").text();
		var position = $(this).find("[data-position]").text();
		var info = $(this).find("[data-info]").attr("data-info");
		var twitter = $(this).find(".info").attr("data-twitter");

		$(".chairperson-active").find(".picture-wrapper img").attr("src", image);
		$(".chairperson-active").find("[data-name]").text(name);
		$(".chairperson-active").find("[data-position]").text(position);
		$(".chairperson-active").find("[data-info]").text(info);
		if (twitter) {
			$(".chairperson-active").find("[data-twitter]").show();
			$(".chairperson-active").find("[data-twitter]").attr("href", twitter);
		} else {
			$(".chairperson-active").find("[data-twitter]").hide();
		}

		$(".chairperson-active").slideDown(function(){
			$('html, body').animate({
				scrollTop: $(this).offset().top
			}, 500);
		});
	});

	// Services list forms
	$(".list-job").click(function(){
		$(".list-job").removeClass("active");
		$(this).addClass("active");

		var formName = $(this).attr("data-form");

		$(".services-content").hide();
		$(".services-content[data-form='"+ formName +"']").slideDown(function(){
			$('html, body').animate({
				scrollTop: $(this).offset().top
			}, 500);
		});
	});

	// Open map modal
	$(".countries svg path").click(function(){
		var country = $(this).attr("data-country");

		$(".info-modal[data-modal='"+country+"']").show(function(){
			$("html").addClass("overflow-hidden");
		});
	});

	// Close map modal
	$(".info-modal .close-modal").click(function(){
		$(".info-modal").hide();
		$("html").removeClass("overflow-hidden");
	});

	// Custom Select Box
	$('.custom-select-wrapper input').on('click', function(){
		$(this).closest('.custom-select-wrapper').find('ul').toggleClass('d-block');
	});
	$('.custom-select-wrapper ul li').on('click', function(){
		var li = $(this);
		li.closest('.custom-select-wrapper').find('input[type="hidden"]').val(li.data('value'));
		li.closest('.custom-select-wrapper').find('input[type="hidden"]').trigger('change');
		li.closest('.custom-select-wrapper').find('input[readonly]').val(li.text());
		li.closest('.custom-select-wrapper').find('ul').removeClass('d-block');
	});
	$(document).bind('click',function(e) {
		if ($(e.target).closest('.custom-select-wrapper').length) return;
		$('.custom-select-wrapper ul').removeClass('d-block');
	});

	var countriesArray = {
		"subject_1": {
			"id": "subject_1",
			"legends": {
				data: [
				{
					name: 'State member'
				},
				{
					name: "Signatory state",
				},
				{
					name: 'Non State Member',
				}
				],
			},
			"countries": {
				data: [
				{
					name: 'syria',
					legend_id: "2"
				},
				{
					name: 'somalia',
					legend_id: "3"
				}
				]
			}
		},
		"subject_2": {
			"id": "subject_2",
			"legends": {
				data: [
				{
					name: 'State member'
				},
				{
					name: "Signatory state",
				},
				{
					name: 'Non State Member',
				}
				],
			},
			"countries": {
				data: [
				{
					"name": "lebanon",
					"legend_id": "3"
				},
				{
					name: 'syria',
					legend_id: "2"
				},
				{
					name: 'yemen',
					legend_id: "2"
				},
				{
					name: 'libiya',
					legend_id: "2"
				},
				{
					name: 'mauritania',
					legend_id: "2"
				},
				{
					name: 'djibouti',
					legend_id: "2"
				},
				{
					name: 'somalia',
					legend_id: "3"
				}
				]
			}
		},
		"subject_3": {
			"id": "subject_3",
			"legends": {
				data: [
				{
					name: 'Represented in ACINET and its Non-governmental Group'
				},
				{
					name: "Member of ACINET",
				},
				{
					name: '	Not Represented in ACINET and its Non-governmental Group',
				}
				],
			},
			"countries": {
				data: [
				{
					"name": "lebanon",
					"legend_id": "3"
				},
				{
					name: 'syria',
					legend_id: "2"
				},
				{
					name: 'yemen',
					legend_id: "2"
				},
				{
					name: 'libiya',
					legend_id: "2"
				},
				{
					name: 'mauritania',
					legend_id: "2"
				},
				{
					name: 'djibouti',
					legend_id: "2"
				},
				{
					name: 'somalia',
					legend_id: "3"
				}
				]
			}
		}
	};

    // Map's countries functionalities
    $('.map-select.custom-select-wrapper input[type="hidden"]').change(function(){
    	var value = $(this).val();

    	$(".countries .legends li").remove();
    	$(".countries svg path").removeClass("green");
    	$(".countries svg path").removeClass("yellow");
    	$(".countries svg path").removeClass("red");
    	if(value == countriesArray[value].id){
    		for(var i = 0; i < countriesArray[value].legends.data.length; i++){
    			$(".countries .legends").append('<li class="list-inline-item mr-4"><span></span>'+ countriesArray[value].legends.data[i].name +'</li>');
    		}
    		for(var j = 0; j < countriesArray[value].countries.data.length; j++){
    			var className = $(".countries svg path[data-country='"+countriesArray[value].countries.data[j].name+"']");
    			if(countriesArray[value].countries.data[j].legend_id == 1){
    				className.addClass("green");
    			} else if(countriesArray[value].countries.data[j].legend_id == 2){
    				className.addClass("yellow");
    			} else if(countriesArray[value].countries.data[j].legend_id == 3){
    				className.addClass("red");
    			}
    		}
    	} else {
    		$(".countries .legends").append("<li>No data available</li>");
    	}

    });

    $('form[ajax]').on('submit', function(e){
    	e.preventDefault();

    	var form = $(this);
    	var formData = new FormData(form[0]);
    	var formSubmit = form.find('[type="submit"]');

    	if (formSubmit[0].disabled) return;
    	formSubmit[0].disabled = true;

    	if (form.find('.message-alert-wrapper').length) {
	    	form.find('.message-alert-wrapper').slideUp(function(){
		    	form.find('.alert-danger').slideUp(function(){
				    submitFormAjax(form, formData, formSubmit);
		    	});
	    	});
	    } else {
	    	form.find('.alert-danger').slideUp(function(){
	    		submitFormAjax(form, formData, formSubmit);
	    	});
	    }
    });

    $('.input-file').on('change', function(e){
    	var input_container = $(this).closest('.input-file-container');
    	var the_return = input_container.find('.file-return');
		the_return.html("(" + e.target.files.length + ")");
    })

});

// Close outise to close share buttons
$(document).mouseup(function(e){
	var container = $(".share-wrapper");
	if (!container.is(e.target) && container.has(e.target).length === 0)
	{
		container.removeClass("show");
	}
});

function submitFormAjax(form, formData, formSubmit) {
	$.ajax({
		type: 'post',
		url: form.attr('action'),
		data: formData,
		cache: false,
		contentType: false,
		processData: false,
		complete: function (r) {
			formSubmit[0].disabled = false;
		},
		success: function(r) {
			form[0].reset();
			form.find('.file-return').text('');
			form.find('[data-print-url]').attr('href', form.find('[data-print-url]').data('print-url') + '/' + r[0] + '/' + r[1]);
			form.find('.message-alert-wrapper').slideDown();
		},
		error: function(r) {
			var errors = '';
			if (r.status == 422) {
				$.each(r.responseJSON.errors, function(i, item){
					errors += '<p>' + item[0] + '</p>';
				});
			} else {
				errors = '<p>Unexpected error</p>';
			}
			form.find('.alert-danger').html(errors);
			form.find('.alert-danger').slideDown();
		}
	});
}


// About counter values
var cnt = 0;
function count() {
	var counter = $(".count").eq(cnt);
	var countTo = counter.attr('data-value');

	$({
		countNum: counter.text()
	}).animate({
		countNum: countTo
	},
	{
		duration: 1000,
		step: function() {
			counter.text(Math.floor(this.countNum));
		},
		complete: function() {
			counter.text(this.countNum);
			cnt++;
			if (cnt < $('.count').length) {
				count();
			}
		}
	});
}

// picture Auto Wrapper
function pictureWrapper(){
	$(".picture-wrapper").each(function(){
		var paddingTop = $(this).attr("data-top");
		$(this).css("padding-top", paddingTop + "%");
	})
}
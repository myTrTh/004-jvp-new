// NAVIGATION

$(document).ready(function() {
	$('.nav-toggle').on('click', function() {
		$('nav > ul > li').not('.logo').slideToggle('fast');
	});

	$(window).resize(function() {
		if ( $(window).width() > 750) {
			$('nav > ul > li').removeAttr('style');
		}
	});	
});
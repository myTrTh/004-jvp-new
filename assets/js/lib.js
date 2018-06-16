// menu show 
$(function(){
	$('nav li').on('mouseenter', function(e){
	  e.preventDefault(); 
	  $('ul.down-menu', this).css({'display':'flex'});
	});
});

// menu hide //
$(function(){
	$('nav li').on('mouseleave', function(e){
	  e.preventDefault(); 
	  $('ul.down-menu', this).css({'display':'none'}).hide().slideUp();
	});
});

// set timezone
$(function() {
	var date = new Date();
	// var loc = Date(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
	var loc = date.getTimezoneOffset()/60;
	document.cookie = "timezone=" + loc + "; path=/";
})
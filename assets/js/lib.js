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


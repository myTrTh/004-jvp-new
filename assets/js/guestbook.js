// bb code
$(function(){
	$('.bbimg').on('click', function(){
		
		// var ttbody = $(this).parent().parent();
		var bbtag = $(this).attr('id');
		var textbody = $('textarea');

		// Набор bb кодов
		if (bbtag == 'post') {
			var tag_start = 'post:';
			var tag_end = '';
		} else {
			var tag_start = '[' + bbtag + ']';
			var tag_end = '[/' + bbtag + ']';
		}


		var start = textbody[0].selectionStart;
		var end = textbody[0].selectionEnd;
		var alltext = textbody.val();
		var needtext = textbody.val().substr(start, end - start);
		var bb_and_text = tag_start + needtext + tag_end;

		var cursor_position = textbody.val().length;

		var start_text = alltext.substr(0, start);
		var end_text = alltext.substr(end, cursor_position);
		textbody.val(start_text + bb_and_text + end_text);
		var newlength = textbody.val().length;
		textbody.focus();

		if (needtext.length == 0) {
			textbody[0].setSelectionRange(start+tag_start.length, start+tag_start.length);			
		} else {
			textbody[0].setSelectionRange(start, end+tag_start.length+tag_end.length);
		}

	});
});



// quote
$(function(){
	$('.quote').on('click', function(){
		var quoteid = $(this).attr('id');
		var id = quoteid.substr(5);
		var user = $(this).parent().parent().parent().children().children().children().html().trim();
		var date = $('#hidden-date-' + id).text().trim();
		var message = $('#hidden-message-' + id).text().trim();
		var quote_text = '[quote author=' + user + ' date=' + date +' post=' + id + ']\n' + message + '\n[/quote]\n\n';
		var textarea = $('textarea');
		var start = textarea[0].selectionStart;
		var end = textarea[0].selectionEnd;
		var alltext = textarea.val();
		var start_text = alltext.substr(0, start);
		var cursor_position = textarea.val().length;
		var end_text = alltext.substr(end, cursor_position);
		textarea.val(start_text + quote_text + end_text);
		textarea.focus();
		textarea[0].setSelectionRange(start+quote_text.length, start+quote_text.length);

		// $("html, body").animate({ scrollTop: 320 }, 500);

		return false;
	});
})



// show smile
$(function(){
	$('.headsmile').on('click', function(){
		$('.smilepanel').slideToggle(200);
	})
})



/* add smiles */
$(function(){
	$('.smiles').on('click', function(){
		
		var smile = $(this).attr('id');
		var textbody = $('textarea');

		// Набор bb кодов
		var tag_start = smile;
		var tag_end = '';

		var start = textbody[0].selectionStart;
		var end = textbody[0].selectionEnd;
		var alltext = textbody.val();
		var needtext = textbody.val().substr(start, end - start);
		var bb_and_text = tag_start + needtext + tag_end;

		var cursor_position = textbody.val().length;

		var start_text = alltext.substr(0, start);
		var end_text = alltext.substr(end, cursor_position);
		textbody.val(start_text + bb_and_text + end_text);
		var newlength = textbody.val().length;
		textbody.focus();
		if(needtext.length == 0){
			textbody[0].setSelectionRange(start+tag_start.length, start+tag_start.length);			
		} else {
			textbody[0].setSelectionRange(start, end+tag_start.length+tag_end.length);
		}

	});
})

// spoiler
$(function(){
	$(document).on('click', '.spoiler-name', function() {	
		var head = $(this).parent();
		var spoiler = head.next();
		spoiler.slideToggle(300);

		var sign = $('.sign:first', head).html();
		if(sign == '+')
			$('.sign:first', head).html('−');
		else
			$('.sign:first', head).html('+');		
	});
})

// nach //
$(function(){
	$('div[id^="nach"]').on('mouseenter', function(e){
		e.preventDefault();
		$('.toolkit', this).stop(true, false).slideDown(200);
	})
})

$(function(){
	$('div[id^="nach"]').on('mouseleave', function(e){
		e.preventDefault();
		$('.toolkit', this).stop(true, false).slideUp(200);
	})
})

// top nach //
$(function(){
	$('div[id^="topnach"]').on('mouseenter', function(e){
		e.preventDefault();
		$('.toptoolkit', this).stop(true, false).fadeIn(200);
	})
})

$(function(){
	$('div[id^="topnach"]').on('mouseleave', function(e){
		e.preventDefault();
		$('.toptoolkit', this).stop(true, false).fadeOut(200);
	})
})

$(function() {
	$('.edit-post').on('click', function() {
		var idpost = $(this).attr('id');
		var id = idpost.substr(4);

		var message = $('#hidden-message-' + id).text().trim();
		var textarea = $('textarea');
		var start = textarea[0].selectionStart;
		var end = textarea[0].selectionEnd;
		var alltext = textarea.val();
		var start_text = alltext.substr(0, start);
		var cursor_position = textarea.val().length;
		var end_text = alltext.substr(end, cursor_position);
		textarea.val(message);
		textarea.focus();
		textarea[0].setSelectionRange(start+message.length, start+message.length);
		$('input:submit').val('Редактировать');
		$('#guestbook-form').append("<input type='hidden' name='edit' value='" + id + "'>");
	})
})
// up down rate //
$(function(){
	$('.tumbler i').on('click', function(){
		var row = $(this).parent().attr('id');
		var sign = row.substr(0, 1);
		var id = row.substr(1);

		var token = $('#token').attr('data-token');
		$('#u' + id).html('');
		$('#d' + id).html('');

		var senddata = 'id='+escape(id)+'&sign='+escape(sign)+'&csrf_token='+escape(token);
		$.ajaxSetup({cache: false}); 
		$.ajax({
			url: "/ajax/guestbook/rate",
			data: senddata,
			type: "POST",
			dataType: "json",
			success: function(data){
				if (data.error == 1) {

					alert(data.error_message);
				} else {
					// update message rate
					if (data.message_sum_rates > 0) {
						$('#l' + id).html("<span class='rate-high'>+" + data.message_sum_rates + "</span>");
					} else if (data.message_sum_rates == 0) {
						$('#l' + id).html("<span class='rate-middle'>" + data.message_sum_rates + "</span>");
					} else if (data.message_sum_rates < 0) {
						$('#l' + id).html("<span class='rate-low'>" + data.message_sum_rates + "</span>");
					}

					// update user rate (author message)
					if (data.author_sum_rates > 0) {
						$('.r' + data.user).html("<div class='rate-high'><i class='fa fa-plus-circle' aria-hidden='true'></i> " + data.author_sum_rates + "</div>");
					} else if (data.author_sum_rates == 0) {
						$('.r' + data.user).html("<div class='rate-middle'><i class='fa fa-circle' aria-hidden='true'></i> " + data.author_sum_rates + "</div>");
					} else if (data.author_sum_rates < 0) {
						$('.r' + data.user).html("<div class='rate-low'><i class='fa fa-minus-circle' aria-hidden='true'></i> " + Math.abs(data.author_sum_rates) + "</div>");
					}

					// update plus and minus users
					$('.rate-users-up-' + id).html(data.plus_users);
					$('.rate-users-down-' + id).html(data.minus_users);
					$('.rate-users-no-' + id).html('');
				}
			}
		})
	})
})

// show rank //
$(function(){
	$('.rate-level').on('mouseenter mouseleave', function(){
		var parent = $(this).parent();
		$('.rate-panel-users', parent).stop(true, false).slideToggle(400);
	})
})
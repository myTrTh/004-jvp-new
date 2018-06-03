// show users info in admin panel
$(function() {
	$('.admin-panel-users').on('click', function() {
		$(this).next().slideToggle(300);
	});
});

// set permissions and roles
$(function() {
	$('[id ^= "set-permissions"]').on('click', function() {
		var button = $(this).attr('id');
		var user = button.substr(16);
		var token = $('#form-per-' + user + ' input[name="_csrf_token"]').val();
		var permissions = [];
		$('#form-per-' + user + ' input:checkbox:checked').each(function() {
			permissions.push($(this).val());
		});
		var senddata = 'user='+encodeURIComponent(user)+'&permissions='+encodeURIComponent(permissions)+'&_csrf_token='+encodeURIComponent(token);
		$.ajaxSetup({cache: false}); 
		$.ajax({
			url: "/ajax/admin/permissions",
			data: senddata,
			type: "POST",
			dataType: "json",
			success: function(data){
				if (data.success == 1) {
					$('.success-pfield-' + user).html(data.message);
				} else {
					$('.error-pfield-' + user).html(data);
				}
			}
		});
	})
})

// set permissions and roles
$(function() {
	$('[id ^= "set-roles"]').on('click', function() {
		var button = $(this).attr('id');
		var user = button.substr(10);
		var token = $('#form-rol-' + user + ' input[name="_csrf_token"]').val();
		var roles = [];
		$('#form-rol-' + user + ' input:checkbox:checked').each(function() {
			roles.push($(this).val());
		});
		console.log(roles);
		var senddata = 'user='+encodeURIComponent(user)+'&roles='+encodeURIComponent(roles)+'&_csrf_token='+encodeURIComponent(token);		
		$.ajaxSetup({cache: false}); 
		$.ajax({
			url: "/ajax/admin/roles",
			data: senddata,
			type: "POST",
			dataType: "json",
			success: function(data){
				if (data.success == 1) {
					$('.success-rfield-' + user).html(data.message);
				} else {
					$('.error-rfield-' + user).html(data);
				}
			}
		});
	})
})
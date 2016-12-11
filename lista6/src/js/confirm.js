$(document).ready(function() {	
	$('[id^=confirm_]').click(function() {
		var id = $(this).attr('id');
		id = id.substring(id.indexOf('_')+1, id.length);
		$.post("adminConf.php", {id: id}) 
			.done(function(data) {
				if (data == 1) {
					window.location.href = 'confirm.php';
				} else {
					console.error('Problems!');
				}
			});
	});

	$('[id^=reject]').click(function() {
		var id = $(this).attr('id');
		id = id.substring(id.indexOf('_')+1, id.length);
		$.post("adminRej.php", {id: id}) 
			.done(function(data) {
				if (data == 1) {
					window.location.href = 'confirm.php';
				} else {
					console.error('Problems!');
				}
			});
	});
});
$(document).ready(function(){
	var addr = window.location.href.substring(19, window.location.href.length);
	if (addr.length == 0) {
		addr = 'index.php';
	}
	$('ul li a').each(function() {
		var a = $(this)[0].attributes[0].nodeValue;
		if (a == addr) {
			$(this).addClass('chosen');
		}
	});
});
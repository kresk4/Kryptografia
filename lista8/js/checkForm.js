var sum, title, id, receiver, address;
$(document).ready(function() {
	var inside = false;
	$('.specialButton#2nd').hide();
	$('.specialButton#1st').click(function() {
		var isEmpty = -1, idErr = -1, numErr = -1;
		if ($('.warningMsg').length > 0)	$('.warningMsg').remove();
		if ($('.errorMsg').length > 0)		$('.errorMsg').remove();
		$('.panel#transferContent input').each(function() {
			if ($(this).val().length == 0) {
				if (idErr == -1 && numErr == -1 && isEmpty == -1) {
					isEmpty = 1;
					$('.panel#transferContent').after('<div class="warningMsg">Żadne z pól nie może pozostać puste.</div>');
				}
				return false;
			}
		});
		id = $('.panel#transferContent input[name="id"]').val();
		if (id.length != 32) {
			if (numErr == -1 && isEmpty == -1) {
				$('.panel#transferContent').after('<div class="errorMsg">Niewłaściwa długość numeru rachunku bankowego.</div>');
			}
			idErr = 1;
		}
		sum = $('.panel#transferContent input[name="sum"]').val();
		var count = (sum.match(/\./g) || []).length;
		if (count > 1 || (count == 1 && sum.indexOf('.') > -1 && sum.indexOf('.') < sum.length-3) || sum.indexOf('.') == sum.length-1) {
			if (idErr == -1 && isEmpty == -1) {
				$('.panel#transferContent').after('<div class="errorMsg">Niewłaściwy format kwoty przelewu.</div>');
			}
			numErr = 1;
		}
		if (isEmpty == -1 && idErr == -1 && numErr == -1) {
			$('.panel#transferContent input[type="text"]').each(function() {
				$(this).prop('disabled', true);
				$(this).addClass('locked');
			});

			$('.specialButton#2nd').show();
			$('.specialButton#1st').hide();
			
			if($('.editData').length == 0) {
				$('.panel#transferContent').after('<div class="editData">Edytuj dane</div>');
			}
			$('.editData').click(function() {
				$('.specialButton#2nd').hide();
				$('.specialButton#1st').show();
				$('.editData').remove();
				$('.panel#transferContent input[type="text"]').each(function() {
					$(this).prop('disabled', false);
					$(this).removeClass('locked');
				});
			});
			$('.specialButton#2nd').click(function() {
				inside = true;
				$('.editData').remove();
				receiver = $('.panel#transferContent input[name="receiver"]').val();
				address = $('.panel#transferContent input[name="address"]').val();
				title = $('.panel#transferContent input[name="title"]').val();
				if (inside) {
					$.post("authTransfer.php", {
						receiver: receiver,
						id: 			id,
						address: 	address,
						title: 		title,
						sum: 			sum,
						username: login
					}) 
					.done(function(data) {
						if (data == "1") {
							$('#transferContent').css('width', '550px')
							$('#transferContent').html('<h2>Sukces!</h2>Zlecenie przelewu zostało zarejestrowane i czeka na potwierdzenie przez administratora.<br/> \
								<table id="summary"><tr><td>Nazwa odbiorcy:</td><td>' + receiver + '</td></tr> \
								<tr><td>Numer rachunku bankowego:</td><td>' + id + '</td></tr> \
								<tr><td>Adres odbiorcy:</td><td>' + address + '</td></tr> \
								<tr><td>Tytuł przelewu:</td><td>' + title + '</td></tr> \
								<tr><td>Kwota:</td><td>' + sum + '</td></tr></table> \
								<div class="specialButton" id="third">Powrót</div> \
							');
							$('.specialButton#third').click(function() {
								window.location.href = 'index.php';
							});
						} else {
							console.log('err!');
						}
					});
				}
			});
		}
	});
});

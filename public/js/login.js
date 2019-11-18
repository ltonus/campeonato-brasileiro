$(function() {
	$('#login').on('click', function() {
		var data = {
			'action': 'signin',
			'login': $('#usuario').val(),
			'senha': md5($('#senha').val())
		};

		$.post('/campeonato-brasileiro/application/controllers/login.controller.php', data, function(res) {
			res = JSON.parse(res);

			if (res.length) {
				var msg = '';

				$.each(res, function() {
					msg += this;
				});

				alert(msg);
			} else {
				window.location = '/campeonato-brasileiro/public/classificacao.php';
			}
		});

		return false;
	});
});
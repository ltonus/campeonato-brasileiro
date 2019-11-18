$(function() {
	$('#logout').on('click', function() {
		$.post('/campeonato-brasileiro/application/controllers/login.controller.php', {'action': 'signout'}, function() {
			window.location = '/campeonato-brasileiro/public/login.php';
		});
	});

	$('#gerar_rodadas').on('click', function() {
		$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'gerarRodadas'}, function() {

		});
	});
});
$(function() {
	$('#logout').on('click', function() {
		$.post('/campeonato-brasileiro/application/controllers/login.controller.php', {'action': 'signout'}, function() {
			window.location = '/campeonato-brasileiro/public/login.php';
		});
	});
});

function showWait() {
	$('#loading, #overlay').show();
}

function hideWait() {
	$('#loading, #overlay').hide();
}
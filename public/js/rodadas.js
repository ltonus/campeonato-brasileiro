$(function() {
	$('#left_menu ul li:eq(1)').addClass('selected');
	verificarRodadasGeradas();

	$('#gerar_rodadas').on('click', function() {
		showWait();

		$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'gerarRodadas'}, function() {
			hideWait();
			$('#div_gerar_rodadas').hide();
			$('#rodadas').show();
			obterRodadas();
		});
	});

	$(document).on('click', '.n-rodada', function() {
		$('.n-rodada').removeClass('selecionada');
		$(this).addClass('selecionada');
		obterRodada(parseInt($(this).attr('data-id')));
	});

	$(document).on('click', '.seta-rodadas i', function() {
		if ($(this).parent().hasClass('seta-esquerda')) {
			$('.n-rodada.selecionada').prev().addClass('selecionada');
			$('.n-rodada.selecionada').next().removeClass('selecionada');
		} else {
			$('.n-rodada.selecionada').next().addClass('selecionada');
			$('.n-rodada.selecionada').prev().removeClass('selecionada');
		}

		obterRodada(parseInt($('.n-rodada.selecionada').attr('data-id')));
	});

	$(document).on('click', '#salvar_rodada', function() {
		salvar();
	});

	$(document).on('click', '#fechar_rodada', function() {
		fecharRodada();
	});

	$(document).on('blur', '.div-jogo input', function() {
		if (!$(this).val()) {
			$(this).val('0');
		}
	});
});

function verificarRodadasGeradas() {
	$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'rodadasGeradas'}, function(data) {
		data = JSON.parse(data);

		if (!data.rodadasGeradas) {
			$('#div_gerar_rodadas').show();
		} else {
			$('#rodadas').show();
			obterRodadas();
		}
	});
}

function obterRodadas() {
	$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'obterRodadas'}, function(data) {
		data = JSON.parse(data);
		var idRodadaAtual = 0;

		$.each(data, function() {
			if (!parseInt(this.fechada) && !idRodadaAtual) {
				idRodadaAtual = parseInt(this.id);
			}

			$('#n_rodadas').append(
				$('<div>', {'class': 'n-rodada', 'data-id': this.id, 'text': this.numero})
			);
		});

		$('#n_rodadas .n-rodada[data-id="' + idRodadaAtual + '"]').addClass('selecionada atual');
		obterRodada(idRodadaAtual);
	});
}

function obterRodada(idRodada) {
	$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'obterDadosRodada', 'idRodada': idRodada}, function(data) {
		data = JSON.parse(data);

		$('#jogos_rodada').empty();

		var rodadaAtual = parseInt($('.n-rodada.atual').attr('data-id')) == idRodada;

		$.each(data.jogos, function() {
			$('#jogos_rodada').append(
				$('<div>', {'class': 'jogo-rodada', 'data-id': this.idJogo}).append(
					$('<span>', {'html': '<strong>' + data.rodada.data + '<strong> ' + this.estadio}),
					$('<div>', {'class': 'div-jogo'}).append(
						$('<div>', {'class': 'time-mandante'}).append(
							$('<span>', {'class': 'time-sigla', 'text': this.siglaTimeMandante}),
							$('<img>', {'class': 'img-time', 'src': this.imagemTimeMandante, 'title': this.nomeTimeMandante}),
							$('<input>', {'type': 'text', 'value': this.golTimeMandante, 'disabled': !rodadaAtual})
						),
						$('<div>', {'class': 'time-vs'}).append(
							$('<i>', {'class': 'fas fa-times'})
						),
						$('<div>', {'class': 'time-visitante'}).append(
							$('<input>', {'type': 'text', 'value': this.golTimeVisitante, 'disabled': !rodadaAtual}),
							$('<img>', {'class': 'img-time', 'src': this.imagemTimeVisitante, 'title': this.nomeTimeVisitante}),
							$('<span>', {'class': 'time-sigla', 'text': this.siglaTimeVisitante})
						)
					)
				)
			);
		});

		$('#jogos_rodada .div-jogo input').mask('0#', {
			'selectOnFocus': true,
			'translation': {
				'#': {
					'pattern': /[0-9]/,
					'optional': true
				}
			}
		});

		$('#titulo_rodada').attr('data-numero', $('.n-rodada.selecionada').text());

		if (rodadaAtual) {
			$('#salvar_rodada, #fechar_rodada').show();
		} else {
			$('#salvar_rodada, #fechar_rodada').hide();
		}
	});
}

function salvar() {
	if (confirm('Deseja realmente salvar?')) {
		showWait();

		var jogos = {};

		$.each($('#jogos_rodada .jogo-rodada'), function() {
			jogos[$(this).attr('data-id')] = {
				'golTimeMandante': $(this).find('.time-mandante input').val(),
				'golTimeVisitante': $(this).find('.time-visitante input').val()
			};
		});

		$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'salvar', 'data': JSON.stringify(jogos)}, function() {
			hideWait();
		});
	}
}

function fecharRodada() {
	if (confirm('Certifique-se de ter salvo antes de fechar a rodada.\nDeseja fechar a rodada?')) {
		$.post('/campeonato-brasileiro/application/controllers/rodada.controller.php', {'action': 'fecharRodada', 'idRodada': $('.n-rodada.atual').attr('data-id')}, function(data) {
			hideWait();
			location.reload();
		});
	}
}
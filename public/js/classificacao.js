$(function() {
	$('#left_menu ul li:eq(0)').addClass('selected');

	obterClassificacao();
});

function obterClassificacao() {
	$.post('/campeonato-brasileiro/application/controllers/classificacao.controller.php', {'action': 'obterClassificacao'}, function(data) {
		popularTabelaClassificacao(JSON.parse(data));
	});
}

function popularTabelaClassificacao(data) {
	$('#container table tbody').empty();

	$.each(data.classificacoes, function(i, classificacao) {
		$('#container table tbody').append(
			$('<tr>').append(
				$('<td>', {'text': ++i}),
				$('<td>', {'text': classificacao.time, 'class': 'text-left'}),
				$('<td>', {'text': classificacao.pontuacao}),
				$('<td>', {'text': classificacao.numeroVitoria}),
				$('<td>', {'text': classificacao.numeroEmpate}),
				$('<td>', {'text': classificacao.numeroDerrota}),
				$('<td>', {'text': classificacao.saldoGolPro}),
				$('<td>', {'text': classificacao.saldoGolContra}),
				$('<td>', {'text': classificacao.saldoGolPro - classificacao.saldoGolContra}),
				$('<td>', {'text': (parseInt(classificacao.pontuacao) / (3 * parseInt(data.numeroRodadaAtual)) * 100).toFixed(2)})
			)
		);
	});
}
<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'classificacao.model.php');

class Classificacao extends Classe implements IClass {

	const PONTOS_VITORIA = 3;
	const PONTOS_EMPATE = 1;

	public function __construct() {
		parent::__construct();
		$this->setModel(new ClassificacaoModel());
	}

	public function atualizar() {
		$dadosClassificacao = $this->getConn()->query('SELECT `c`.`id`, `ct`.`idTime`, `c`.`pontuacao`, `c`.`saldoGolPro`, `c`.`saldoGolContra`, `c`.`numeroVitoria`, `c`.`numeroEmpate`, `c`.`numeroDerrota` FROM `classificacao` AS `c` JOIN `campeonato_time` AS `ct` ON `ct`.`id` = `c`.`idCampeonatoTime` WHERE `ct`.`idCampeonato` = ' . Auth::$idCampeonato)->fetch_all(MYSQLI_ASSOC);
		$classificacao = $this->parseDadosClassificacao($dadosClassificacao);

		$rodadaClass = new Rodada();
		$dadosRodada = $rodadaClass->obterRodadaAtual();

		$jogoClass = new Jogo();
		$dadosJogos = $jogoClass->load(['idRodada' => $dadosRodada['id']], ['idTimeMandante', 'idTimeVisitante', 'golTimeMandante', 'golTimeVisitante']);

		foreach ($dadosJogos as $jogo) {
			$idTimeMandante = $jogo['idTimeMandante'];
			$idTimeVisitante = $jogo['idTimeVisitante'];

			if ($jogo['golTimeMandante'] > $jogo['golTimeVisitante']) {
				$classificacao[$idTimeMandante]['numeroVitoria']++;
				$classificacao[$idTimeVisitante]['numeroDerrota']++;
			} else if ($jogo['golTimeMandante'] == $jogo['golTimeVisitante']) {
				$classificacao[$idTimeMandante]['numeroEmpate']++;
				$classificacao[$idTimeVisitante]['numeroEmpate']++;
			} else {
				$classificacao[$idTimeVisitante]['numeroVitoria']++;
				$classificacao[$idTimeMandante]['numeroDerrota']++;
			}

			$classificacao[$idTimeMandante]['saldoGolPro'] += $jogo['golTimeMandante'];
			$classificacao[$idTimeMandante]['saldoGolContra'] += $jogo['golTimeVisitante'];
			$classificacao[$idTimeVisitante]['saldoGolPro'] += $jogo['golTimeVisitante'];
			$classificacao[$idTimeVisitante]['saldoGolContra'] += $jogo['golTimeMandante'];
		}

		foreach ($classificacao as $idTime => &$classificacaoTime) {
			$classificacaoTime['pontuacao'] = ($classificacaoTime['numeroVitoria'] * self::PONTOS_VITORIA) + ($classificacaoTime['numeroEmpate'] * self::PONTOS_EMPATE);
			$this->save($classificacaoTime);
		}
	}

	public function obter() {
		$res = [];
		$rodadaClass = new Rodada();
		$rodadasGeradas = $rodadaClass->load(['idCampeonato' => Auth::$idCampeonato], ['id']);

		if (!empty($rodadasGeradas)) {
			$dadosClassificacao = $this->getConn()->query('SELECT `t`.`nome` AS `time`, `c`.`pontuacao` AS `pontuacao`, `c`.`numeroVitoria`, `c`.`numeroEmpate`, `c`.`numeroDerrota`, `c`.`saldoGolPro`, `c`.`saldoGolContra` FROM `classificacao` AS `c` JOIN `campeonato_time` AS `ct` ON `ct`.`id` = `c`.`idCampeonatoTime` JOIN `time` AS `t` ON `t`.`id` = `ct`.`idTime` WHERE `ct`.`idCampeonato` = ' . Auth::$idCampeonato . ' ORDER BY `c`.`pontuacao` DESC, `t`.`nome` ASC;');
			$res['classificacoes'] = $dadosClassificacao->fetch_all(MYSQLI_ASSOC);

			$dadosRodada = $rodadaClass->obterRodadaAtual();
			$res['numeroRodadaAtual'] = $dadosRodada['numero'];
		}

		return $res;
	}

	private function parseDadosClassificacao($dadosClassificacao) {
		$res = [];

		foreach ($dadosClassificacao as &$classificacaoTime) {
			$idTime = $classificacaoTime['idTime'];
			unset($classificacaoTime['idTime']);

			$res[$idTime] = $classificacaoTime;
		}

		return $res;
	}

}

?>
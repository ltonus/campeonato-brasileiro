<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'rodada.model.php');

class Rodada extends Classe implements IClass {

	private static $nTimes = 20;

	public function __construct() {
		parent::__construct();
		$this->setModel(new RodadaModel());
	}

	public function obterRodadaAtual() {
		$dadosRodada = $this->getConn()->query('SELECT MIN(`id`) AS `id`, `numero` FROM `rodada` WHERE `fechada` = 0 AND `idCampeonato` = ' . Auth::$idCampeonato)->fetch_all(MYSQLI_ASSOC);
		return $dadosRodada[0];
	}

	public function fecharRodada($id) {
		$this->save(['id' => $id, 'fechada' => 1]);
	}

	public function obter($id) {
		$res = [];
		$dadosRodada = $this->load(['id' => $id, 'idCampeonato' => Auth::$idCampeonato], ['data', 'numero', 'fechada']);

		if ($dadosRodada) {
			$dadosJogos = $this->getConn()->query('SELECT `j`.`id` as `idJogo`, `tm`.`sigla` as `siglaTimeMandante`, `tv`.`sigla` as `siglaTimeVisitante`, `tm`.`nome` as `nomeTimeMandante`, `tv`.`nome` as `nomeTimeVisitante`, `tm`.`imagem` as `imagemTimeMandante`, `tv`.`imagem` as `imagemTimeVisitante`, `j`.`golTimeMandante`, `j`.`golTimeVisitante`, `tm`.`estadio` FROM `rodada` AS `r` JOIN `jogo` AS `j` ON `j`.`idRodada` = `r`.`id` JOIN `time` AS `tm` ON `tm`.`id` = `j`.`idTimeMandante` JOIN `time` AS `tv` ON `tv`.`id` = `j`.`idTimeVisitante` WHERE `r`.`idCampeonato` = ' . Auth::$idCampeonato . ' AND `r`.`id` = ' . $id)->fetch_all(MYSQLI_ASSOC);

			$dadosRodada[0]['data'] = DateTime::createFromFormat('Y-m-d', $dadosRodada[0]['data'])->format('d/m/Y');

			$res = [
				'rodada' => $dadosRodada[0],
				'jogos' => $dadosJogos
			];
		}

		return $res;
	}

	public function salvar($jogos) {
		$jogoClass = new Jogo();

		foreach ($jogos as $idJogo => $placar) {
			$jogoClass->save([
				'id' => $idJogo,
				'golTimeMandante' => $placar['golTimeMandante'],
				'golTimeVisitante' => $placar['golTimeVisitante']
			]);
		}
	}

	public function rodadasGeradas() {
		return !empty($this->load(['idCampeonato' => Auth::$idCampeonato], ['id']));
	}

	public function gerarRodadas() {
		$rodadas = [];
		for ($i = 0; $i < (self::$nTimes - 1); $i++) {
			$rodadas[$i] = [];
		}

		if (!$this->load(['idCampeonato' => (int) Auth::$idCampeonato], ['id'])) {
			$times = range(0, self::$nTimes - 1);
			shuffle($times);

			for ($i = 0; $i < (self::$nTimes - 1); $i++) {
				for ($j = 0; ($j < self::$nTimes / 2); $j++) {
					$rodadas[$i][$times[$j]] = $times[self::$nTimes - $j - 1];
				}

				$this->rotacionarTimes($times);
			}
		}

		$this->salvarRodadas($rodadas);
	}

	private function rotacionarTimes(Array &$times) {
		$ultimo = end($times);

		for ($i = (self::$nTimes - 1); $i > 0; $i--) {
			if ($i == 1) {
				$times[$i] = $ultimo;
			} else {
				$times[$i] = $times[$i - 1];
			}
		}
	}

	private function salvarRodadas(Array $times) {
		$jogoClass = new Jogo();
		$campeonatoClass = new Campeonato();
		$dadosCampeonato = $campeonatoClass->load(Auth::$idCampeonato, ['data']);

		$campeonatoTimeClass = new CampeonatoTime();
		$dadosCampeonatoTime = $campeonatoTimeClass->load(['idCampeonato' => Auth::$idCampeonato], ['idTime']);

		$dataCampeonato = new DateTime($dadosCampeonato[0]['data']);

		$rodada = [
			'id' => 0,
			'idCampeonato' => Auth::$idCampeonato,
			'fechada' => 0
		];

		$jogo = [
			'id' => 0,
			'golTimeMandante' => 0,
			'golTimeVisitante' => 0
		];

		for ($i = 0; $i < self::$nTimes - 1; $i++) {
			$rodada['numero'] = $i + 1;
			$rodada['data'] = $dataCampeonato->format('Y-m-d');

			$idRodada = $this->save($rodada);

			foreach ($times[$i] as $timeMandante => $timeVisitante) {
				$jogo['idRodada'] = $idRodada;
				$jogo['idTimeMandante'] = $dadosCampeonatoTime[$timeMandante]['idTime'];
				$jogo['idTimeVisitante'] = $dadosCampeonatoTime[$timeVisitante]['idTime'];

				$jogoClass->save($jogo);
			}

			$dataCampeonato->add(new DateInterval('P' . ($i % 2 ? 4 : 3) . 'D'));
		}

		for ($i = 0; $i < self::$nTimes - 1; $i++) {
			$rodada['numero'] = $i + self::$nTimes;
			$rodada['data'] = $dataCampeonato->format('Y-m-d');

			$idRodada = $this->save($rodada);

			foreach ($times[$i] as $timeMandante => $timeVisitante) {
				$jogo['idRodada'] = $idRodada;
				$jogo['idTimeMandante'] = $dadosCampeonatoTime[$timeVisitante]['idTime'];
				$jogo['idTimeVisitante'] = $dadosCampeonatoTime[$timeMandante]['idTime'];

				$jogoClass->save($jogo);
			}

			$dataCampeonato->add(new DateInterval('P' . ($i % 2 ? 3 : 4) . 'D'));
		}

		$this->gerarClassificacao($dadosCampeonatoTime);
	}

	private function gerarClassificacao($times) {
		$classificacaoClass = new Classificacao();

		foreach ($times as $time) {
			$classificacaoClass->save(['id' => 0, 'idCampeonatoTime' => $time['idTime']]);
		}
	}

}

?>
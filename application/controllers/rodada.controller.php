<?php

require_once('/var/www/html/campeonato-brasileiro/application/config/config.php');
require_once(_MODELS_ . 'campeonato.class.php');
require_once(_MODELS_ . 'campeonato.time.class.php');
require_once(_MODELS_ . 'rodada.class.php');
require_once(_MODELS_ . 'jogo.class.php');

Auth::authenticate();

new RodadaController($_POST['action']);

class RodadaController {

	private static $nTimes = 20;

	public function __construct() {
		$this->action($_POST['action']);
	}

	public function action($action) {
		switch ($action) {
			case 'gerarRodadas':
				$this->gerarRodadas();
				break;
		}
	}

	private function gerarRodadas() {
		$rodadas = [];
		for ($i = 0; $i < (self::$nTimes - 1); $i++) {
			$rodadas[$i] = [];
		}

		$rodadaClass = new Rodada();

		if (!$rodadaClass->load(['idCampeonato' => (int) Auth::$idCampeonato], ['id'])) {
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
		$campeonatoClass = new Campeonato();
		$dadosCampeonato = $campeonatoClass->load(Auth::$idCampeonato, ['data']);

		$campeonatoTimeClass = new CampeonatoTime();
		$dadosCampeonatoTime = $campeonatoTimeClass->load(['idCampeonato' => Auth::$idCampeonato], ['idTime']);

		$rodadaClass = new Rodada();
		$dataCampeonato = new DateTime($dadosCampeonato[0]['data']);
		
		$jogoClass = new Jogo();

		$rodada = [
			'id' => 0,
			'idCampeonato' => Auth::$idCampeonato,
			'aberta' => TRUE
		];

		$jogo = [
			'id' => 0,
			'golTimeMandante' => 0,
			'golTimeVisitante' => 0
		];

		for ($i = 0; $i < (self::$nTimes - 1); $i++) {
			$rodada['numero'] = $i + 1;
			$rodada['data'] = $dataCampeonato->format('Y-m-d');

			$idRodada = $rodadaClass->save($rodada);

			foreach ($times[$i] as $timeMandante => $timeVisitante) {
				$jogo['idRodada'] = $idRodada;
				$jogo['idTimeMandante'] = $dadosCampeonatoTime[$timeMandante]['idTime'];
				$jogo['idTimeVisitante'] = $dadosCampeonatoTime[$timeVisitante]['idTime'];

				$jogoClass->save($jogo);
			}

			$dataCampeonato->add(new DateInterval('P' . ($i % 2 ? 4 : 3) . 'D'));
		}
	}

}

?>
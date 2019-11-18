<?php

require_once('/var/www/html/campeonato-brasileiro/application/config/config.php');
require_once(_MODELS_ . 'campeonato.time.class.php');
require_once(_MODELS_ . 'rodada.class.php');

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
			$times = range(1, self::$nTimes);
			shuffle($times);

			for ($i = 0; $i < (self::$nTimes - 1); $i++) {
				for ($j = 0; ($j < self::$nTimes / 2); $j++) {
					$rodadas[$i][$times[$j]] = $times[self::$nTimes - $j - 1];
				}

				$this->rotacionarTimes($times);
			}
		}
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
		foreach ($times as $index => $time) {
			$rodada = [
				'id' => 0,
				'idCampeonato' => Auth::$idCampeonato,
				'numero' => $index,
				'data' => '2019-05-05',
				'aberta' => TRUE
			];
		}
	}

}

?>
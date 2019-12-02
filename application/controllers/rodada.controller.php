<?php

require_once('/var/www/html/campeonato-brasileiro/application/config/config.php');
require_once(_MODELS_ . 'campeonato.class.php');
require_once(_MODELS_ . 'campeonato.time.class.php');
require_once(_MODELS_ . 'rodada.class.php');
require_once(_MODELS_ . 'jogo.class.php');
require_once(_MODELS_ . 'classificacao.class.php');

Auth::authenticate();

new RodadaController($_POST['action']);

class RodadaController {

	public function __construct() {
		$this->action($_POST['action']);
	}

	public function action($action) {
		switch ($action) {
			case 'gerarRodadas':
				$this->gerarRodadas();
				break;
			case 'rodadasGeradas':
				$this->rodadasGeradas();
				break;
			case 'obterRodadas':
				$this->obterRodadas();
				break;
			case 'obterDadosRodada':
				$this->obterDadosRodada();
				break;
			case 'salvar':
				$this->salvar();
				break;
			case 'fecharRodada':
				$this->fecharRodada();
				break;
		}
	}

	private function gerarRodadas() {
		$rodadaClass = new Rodada();
		$rodadaClass->gerarRodadas();
	}

	private function rodadasGeradas() {
		$rodadaClass = new Rodada();
		echo json_encode(['rodadasGeradas' => $rodadaClass->rodadasGeradas()]);
	}

	private function obterRodadas() {
		$rodadaClass = new Rodada();
		$dadosRodada = $rodadaClass->load(['idCampeonato' => Auth::$idCampeonato], ['id', 'numero', 'fechada']);

		echo json_encode($dadosRodada);
	}

	private function obterDadosRodada() {
		$rodadaClass = new Rodada();
		echo json_encode($rodadaClass->obter((int) $_POST['idRodada']));
	}

	private function salvar() {
		$rodadaClass = new Rodada();
		$rodadaClass->salvar(json_decode($_POST['data'], true));

		$classificacaoClass = new Classificacao();
		$classificacaoClass->atualizar();
	}

	private function fecharRodada() {
		$rodadaClass = new Rodada();
		$rodadaClass->fecharRodada((int) $_POST['idRodada']);
	}

}

?>
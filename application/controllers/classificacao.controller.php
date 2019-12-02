<?php

require_once('/var/www/html/campeonato-brasileiro/application/config/config.php');
require_once(_MODELS_ . 'classificacao.class.php');
require_once(_MODELS_ . 'rodada.class.php');

Auth::authenticate();

new ClassificacaoController($_POST['action']);

class ClassificacaoController {

	public function __construct() {
		$this->action($_POST['action']);
	}

	public function action($action) {
		switch ($action) {
			case 'obterClassificacao':
				$this->obterClassificacao();
				break;
		}
	}

	private function obterClassificacao() {
		$res = [];
		$classificacaoClass = new Classificacao();

		echo json_encode($classificacaoClass->obter());
	}

}

?>
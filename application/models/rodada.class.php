<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'rodada.model.php');

class Rodada extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new RodadaModel());
	}

	public function validar(Array $data): array {
		return [];
	}

}

?>
<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'campeonato.time.model.php');

class CampeonatoTime extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new CampeonatoTimeModel());
	}

	public function validar(Array $data): array {
		return [];
	}

}

?>
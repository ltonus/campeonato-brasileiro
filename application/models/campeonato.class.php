<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'campeonato.model.php');

class Campeonato extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new CampeonatoModel());
	}

}

?>
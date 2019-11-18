<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'campeonato.usuario.model.php');

class CampeonatoUsuario extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new CampeonatoUsuarioModel());
	}

	public function validar(Array $data): array {
		return [];
	}

}

?>
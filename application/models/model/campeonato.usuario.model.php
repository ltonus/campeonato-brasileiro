<?php

require_once(_MODEL_ . 'model.php');

class CampeonatoUsuarioModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('campeonato_usuario');
		$this->setColumns(['idCampeonato', 'idUsuario']);
	}
}

?>
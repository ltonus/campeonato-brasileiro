<?php

require_once(_MODEL_ . 'model.php');

class UsuarioModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('usuario');
		$this->setColumns(['id', 'nome', 'login', 'senha', 'tipo']);
	}
}

?>
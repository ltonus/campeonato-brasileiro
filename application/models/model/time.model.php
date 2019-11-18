<?php

require_once(_MODEL_ . 'model.php');

class TimeModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('classificacao');
		$this->setColumns(['id', 'nome', 'estado', 'cidade', 'estadio', 'imagem']);
	}
}

?>
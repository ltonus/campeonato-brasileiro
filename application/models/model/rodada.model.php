<?php

require_once(_MODEL_ . 'model.php');

class RodadaModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('rodada');
		$this->setColumns(['id', 'idCampeonato', 'numero', 'data', 'aberta']);
	}
}

?>
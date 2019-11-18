<?php

require_once(_MODEL_ . 'model.php');

class JogoModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('jogo');
		$this->setColumns(['id', 'idRodada', 'idTimeMandante', 'idTimeVisitante', 'golTimeMandante', 'golTimeVisitante']);
	}
}

?>
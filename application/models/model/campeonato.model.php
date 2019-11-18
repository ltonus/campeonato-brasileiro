<?php

require_once(_MODEL_ . 'model.php');

class CampeonatoModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('campeonato');
		$this->setColumns(['id', 'data', 'nome']);
	}
}

?>
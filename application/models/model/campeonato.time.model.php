<?php

require_once(_MODEL_ . 'model.php');

class CampeonatoTimeModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('campeonato_time');
		$this->setColumns(['id', 'idCampeonato', 'idTime']);
	}
}

?>
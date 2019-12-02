<?php

require_once(_MODEL_ . 'model.php');

class ClassificacaoModel extends Model {

	private $class;

	function __construct() {
		$this->setTable('classificacao');
		$this->setColumns(['id', 'idCampeonatoTime', 'pontuacao', 'saldoGolPro', 'saldoGolContra', 'numeroVitoria', 'numeroEmpate', 'numeroDerrota']);
	}
}

?>
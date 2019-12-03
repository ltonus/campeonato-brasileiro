<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'jogo.model.php');

class Jogo extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new JogoModel());
	}

	public function obterJogoRodadaAtual($id) {
		$res = [];
		$dadosJogo = $this->getConn()->query('SELECT `tm`.`nome` AS `nomeTimeMandante`, `tv`.`nome` AS `nomeTimeVisitante`, `tm`.`sigla` AS `siglaTimeMandante`, `tv`.`sigla` AS `siglaTimeVisitante` FROM `jogo` AS `j` JOIN `rodada` AS `r` ON `r`.`id` = `j`.`idRodada` JOIN `time` AS `tm` ON `tm`.`id` = `j`.`idTimeMandante` JOIN `time` AS `tv` ON `tv`.`id` = `j`.`idTimeVisitante` WHERE `j`.`id` = ' . $id . ' AND `r`.`idCampeonato` = ' . Auth::$idCampeonato . ' AND `r`.`id` = (SELECT `id` FROM `rodada` WHERE `fechada` = 0 AND `idCampeonato` = ' . Auth::$idCampeonato . ' ORDER BY `id` LIMIT 1)')->fetch_all(MYSQLI_ASSOC);

		if ($dadosJogo) {
			$res = $dadosJogo[0];
		}

		return $res;
	}

}

?>
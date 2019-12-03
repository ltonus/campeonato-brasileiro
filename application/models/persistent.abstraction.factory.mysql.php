<?php

require_once(_MODELS_ . 'PersistentMysql.php');

class MysqlAbstractFactory extends PersistentAbstractionFactory {

	public function createInstance() {
		return new PersistentMysql();
	}

}

?>
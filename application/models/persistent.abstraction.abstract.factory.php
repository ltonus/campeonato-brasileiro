<?php

require_once(_MODELS_ . 'persistent.abstraction.factory.php');
require_once(_MODELS_ . 'persistent.abstraction.factory.mysql.php');

class PersistentAbstraction_AbstractFactory {

	public static function getFactory() {
		switch (CONN_TYPE) {
			case 'mysql':
				$factory = new MysqlAbstractFactory();
				break;
		}

		return $factory;
	}

}

?>
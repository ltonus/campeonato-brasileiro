<?php

require_once('/var/www/html/campeonato-brasileiro/application/config/config.php');
require_once(_MODELS_ . 'usuario.class.php');
require_once(_MODELS_ . 'campeonato.usuario.class.php');

new LoginController($_POST['action']);

class LoginController {

	public function __construct() {
		$this->action($_POST['action']);
	}

	public function action($action) {
		switch ($action) {
			case 'signIn':
				$this->signIn();
				break;
			case 'signOut':
				$this->signOut();
				break;
		}
	}

	private function signIn() {
		$usuarioClass = new Usuario();
		$res = $usuarioClass->signIn([
			'login' => $_POST['login'],
			'senha' => $_POST['senha']
		]);

		echo json_encode($res);
	}

	private function signOut() {
		$usuarioClass = new Usuario();
		$usuarioClass->signOut();
	}

}

?>
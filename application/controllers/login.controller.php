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
			case 'signin':
				$this->signin();
				break;
			case 'signout':
				$this->signout();
				break;
			case 'authenticate':
				$this->authenticate();
				break;
		}
	}

	private function signin() {
		$res = [];

		$usuarioClass = new Usuario();
		$dadosUsuario = $usuarioClass->load([
			'login' => $_POST['login'],
			'senha' => $_POST['senha']
		], ['id', 'nome']);

		if ($dadosUsuario) {
			session_start();

			$campeonatoUsuarioClass = new CampeonatoUsuario();
			$dadosCampeonatoUsuario = $campeonatoUsuarioClass->load(['idUsuario' => $dadosUsuario[0]['id']], ['idCampeonato']);

			$_SESSION['loggedin'] = true;
			$_SESSION['idUsuario'] = $dadosUsuario[0]['id'];
			$_SESSION['nome'] = $dadosUsuario[0]['nome'];
			$_SESSION['idCampeonato'] = $dadosCampeonatoUsuario[0]['idCampeonato'];
		} else {
			$res[] = 'Login ou senha incorreta!';
		}

		echo json_encode($res);
	}

	private function signout() {
		session_start();
		$_SESSION = [];
		session_destroy();
	}

}

?>
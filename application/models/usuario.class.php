<?php

require_once(_MODELS_ . 'classe.class.php');
require_once(_MODEL_ . 'usuario.model.php');

class Usuario extends Classe implements IClass {

	public function __construct() {
		parent::__construct();
		$this->setModel(new UsuarioModel());
	}

	public function signIn($dados) {
		$res = [];
		$dadosUsuario = $this->load($dados, ['id', 'nome']);

		if ($dadosUsuario) {
			if (session_status() == PHP_SESSION_NONE) {
				session_start();
			}

			$campeonatoUsuarioClass = new CampeonatoUsuario();
			$dadosCampeonatoUsuario = $campeonatoUsuarioClass->load(['idUsuario' => $dadosUsuario[0]['id']], ['idCampeonato']);

			$_SESSION['loggedin'] = true;
			$_SESSION['idUsuario'] = $dadosUsuario[0]['id'];
			$_SESSION['nome'] = $dadosUsuario[0]['nome'];
			$_SESSION['idCampeonato'] = $dadosCampeonatoUsuario[0]['idCampeonato'];
		} else {
			$res[] = 'Login ou senha incorreta!';
		}

		return $res;
	}

	public function signOut() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		$_SESSION = [];

		session_destroy();
	}

}

?>
<?php

class Auth {

	public static $idCampeonato = 0;

	public static function authenticate() {
		session_start();

		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
			header('location: /campeonato-brasileiro/public/login.php');
		} else {
			self::$idCampeonato = $_SESSION['idCampeonato'];
		}
	}

	public static function redirectLogedIn() {
		session_start();

		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
			header('location: /campeonato-brasileiro/public/classificacao.php');
		}
	}

}

?>

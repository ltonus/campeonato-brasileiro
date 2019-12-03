<?php

class Auth {

	public static $idCampeonato = 0;

	public static function authenticate($redirect = false) {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
			if ($redirect) {
				header('location: /campeonato-brasileiro/public/login.php');
			}

			return false;
		} else {
			self::$idCampeonato = $_SESSION['idCampeonato'];

			return true;
		}
	}

	public static function redirectLogedIn() {
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
			header('location: /campeonato-brasileiro/public/classificacao.php');
		}
	}

	public static function setIdCampeonato() {
		self::$idCampeonato = $_SESSION['idCampeonato'];
	}

}

?>

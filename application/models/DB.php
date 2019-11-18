<?php

class DB {

	private $conn;

	public function __construct() {
		$this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->conn->set_charset('utf8');
	}

	public function query(String $query) {
		return $this->conn->query($query);
	}

	public function getConn() {
		return $this->conn;
	}

	public function __destruct() {
		$this->conn = NULL;
	}

}

?>
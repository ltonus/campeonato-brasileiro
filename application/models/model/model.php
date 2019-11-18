<?php

require_once(_MODEL_ . 'IModel.php');

abstract class Model implements IModel {

	private $table;
	private $columns = [];
	private $record = [];

	public function setTable($table): void {
		$this->table = $table;
	}

	public function getTable(): String {
		return $this->table;
	}

	public function setColumns($columns): void {
		$this->columns = $columns;
	}

	public function setColumnsValues(Array $data): void {
		$this->record = [];

		$data['id'] = $data['id'] ?? 0;

		foreach ($data as $key => $value) {
			$this->record[$key] = $value;
		}
	}

	public function getRecord(): Array {
		return $this->record;
	}

}

?>
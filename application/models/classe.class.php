<?php

require_once(_MODELS_ . 'IClass.php');
require_once(_MODELS_ . 'persistent.abstraction.abstract.factory.php');

abstract class Classe {

	private $db;
	private $model;

	public function __construct() {
		$abstractfactory = new PersistentAbstraction_AbstractFactory();
		$factory = $abstractfactory::getFactory();
		$this->db = $factory->createInstance();
	}

	public function setModel(Model $model): void {
		$this->model = $model;
	}

	public function save(Array $data): int {
		$this->model->setColumnsValues($data);

		if ($data['id'] > 0) {
			$res = $this->update($data);
		} else {
			$res = $this->insert($data);
		}

		return $res;
	}

	public function insert(Array $data): int {
		$res = 0;

		$columns = $this->formatColumnsInsert(array_keys($data));
		$values = $this->formatValuesInsert(array_values($data));
		$query = 'INSERT INTO ' . $this->model->getTable() . ' (' . $columns . ') VALUES (' . $values . ')';

		if ($this->db->query($query)) {
			$res = $this->db->getConn()->insert_id;
		}

		return $res;
	}

	public function update(Array $data): int {
		$fields = $this->formatValues($data, true);
		$query = 'UPDATE ' . $this->model->getTable() . ' SET ' . $fields . ' WHERE `id` = ' . (int) $data['id'];

		return (int) $this->db->query($query);
	}

	public function delete($selector): bool {
		if (!is_array($selector)) {
			$params['id'] = (int) $selector;
		} else {
			$params = $selector;
		}

		$where = $this->formatValues($params);
		$query = 'DELETE FROM ' . $this->model->getTable() . ' WHERE ' . $where;

		return $this->db->query($query);
	}

	public function load($selector, Array $columns = []) {
		if (!is_array($selector)) {
			$params['id'] = (int) $selector;
		} else {
			$params = $selector;
		}

		$where = $this->formatValues($params);
		$query = 'SELECT ' . (empty($columns) ? '*' : '`' . join('`, `', $columns) . '`') . ' FROM ' . $this->model->getTable() . ' WHERE ' . $where;

		$result = $this->db->query($query);

		return $result->fetch_all(MYSQLI_ASSOC);
	}

	private function formatColumnsInsert(Array $columns): String {
		$res = [];

		foreach ($columns as $column) {
			$res[] = '`' . $column . '`';
		}

		return join(', ', $res);
	}

	private function formatValuesInsert(Array $values): String {
		$res = [];

		foreach ($values as $value) {
			$res[] = $this->escapeString($value);
		}

		return join(', ', $res);
	}

	private function formatValues(Array $data, $update = false): String {
		$res = [];

		foreach ($data as $key => $value) {
			if ($update && $key == 'id') {
				continue;
			}

			$res[] = '`' . $key . '` = ' . $this->escapeString($value);
		}

		return join($update ? ', ' : ' AND ', $res);
	}

	private function escapeString($value): String {
		return (is_string($value) ? "'" . $this->db->getConn()->real_escape_string($value) . "'" : $value);
	}

	public function validar(Array $data): array {
		return [];
	}

	public function getConn() {
		return $this->db->getConn();
	}

}

?>
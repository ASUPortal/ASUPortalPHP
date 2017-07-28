<?php

/**
 * Сервис по работе с версиями
 *
 */
class CVersionControlService {
	protected $items = array();
	
	/**
	 * Получить все версии записи по id и таблице
	 * 
	 * @param int $id - идентификатор последней записи
	 * @param string $table - таблица, из которой берём значения
	 * @return array
	 */
	public function getVersions($id, $table) {
		return $this->tree($id, $table);
	}
	
	/**
	 * Рекурсивное получение записей по id версий
	 * 
	 * @param int $id - идентификатор последней записи
	 * @param string $table - таблица, из которой берём значения
	 * @return array
	 */
	function tree($id, $table) {
		$query = new CQuery();
		$query->select("t.*")
			->from($table." as t")
			->condition("t.id = ".$id);
		$item = $query->execute()->getFirstItem();
		array_push($this->items, $item);
		if ($item["_version_of"] != 0) {
			$this->tree($item["_version_of"], $table);
		}
		return $this->items;
	}
}
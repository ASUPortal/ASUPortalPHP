<?php

/**
 * Сервис по работе с версиями
 *
 */
class CVersionControlService {
	protected $items = array();
	
	/**
	 * Получить все версии записи по id и классу
	 * 
	 * @param int $id - идентификатор последней записи
	 * @param string $class - модельный класс записи
	 * @return CVersionControlService
	 */
	public static function getVersions($id, $class) {
		$version = new CVersionControlService();
		$version->tree($id, $class);
		return $version->items;
	}
	
	/**
	 * Рекурсивное получение записей по id версий
	 * 
	 * @param int $id - идентификатор последней записи
	 * @param string $class - модельный класс записи
	 * @return array
	 */
	function tree($id, $class) {
		$simpleClass = new $class();
		$table = $simpleClass->getRecord()->getTable();
		$query = new CQuery();
		$query->select("t.*")
			->from($table." as t")
			->condition("t.id = ".$id);
		$item = $query->execute()->getFirstItem();
		array_push($this->items, $item);
		if ($item["id"] != $item["_version_of"]) {
			$this->tree($item["_version_of"], $class);
		}
		return $this->items;
	}
	
	/**
	 * Удаление записи по id и классу
	 * 
	 * @param int $id - идентификатор последней записи
	 * @param string $class - модельный класс записи
	 */
	public function delete($id, $class) {
		$simpleClass = new $class();
		$table = $simpleClass->getRecord()->getTable();
		 
		$ar = CActiveRecordProvider::getById($table, $id);
		if (!is_null($ar)) {
			$item = new $class($ar);
			$item->remove();
		}
	}
}
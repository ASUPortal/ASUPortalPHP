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
	 * @param int $id
	 * @return array
	 */
	public function getVersions($id, $class, $table) {
		return $this->tree($id, $class, $table);
	}
	
	function tree($id, $class, $table) {
		
		$query = new CQuery();
		$query->select("t.*")
			->from($table." as t")
			->condition("t.id = ".$id);
		$item = $query->execute()->getFirstItem();
		array_push($this->items, $item);
		if ($item["_version_of"] != 0) {
			$this->tree($item["_version_of"], $class, $table);
		}
		return $this->items;
	}
}
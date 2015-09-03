<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 20:34
 * To change this template use File | Settings | File Templates.
 *
 * POPO - простой PHP-объект для структурирования данных
 */

class CLibraryFolder{
    private $_discipline = null;
    private $_persons = null;
    private $_users = null;
    private static $_materials = null;
    private $_folders = null;

    public function __construct(CTerm $discipline) {
        $this->_discipline = $discipline;
    }
    public function getDiscipline() {
        return $this->_discipline;
    }

    /**
     * Преподаватели, которые ведут дисциплину из этой папки
     *
     * @return CArrayList|null
     */
    public function getPersons() {
        if (is_null($this->_persons)) {
            $this->_persons = new CArrayList();
            foreach (CActiveRecordProvider::getWithCondition(TABLE_LIBRARY_DOCUMENTS, "subj_id = ".$this->getDiscipline()->getId())->getItems() as $ar) {
                $user = CStaffManager::getUser($ar->getItemValue("user_id"));
                if (!is_null($user)) {
                    if (!is_null($user->getPerson())) {
                        $person = $user->getPerson();
                        $this->_persons->add($person->getId(), $person);
                    }
                }
            }
        }
        return $this->_persons;
    }
    /**
     * Пользователи, которые ведут дисциплину из этой папки
     *
     * @return CArrayList|null
     */
    public function getUsers() {
    	if (is_null($this->_users)) {
    		$this->_users = new CArrayList();
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_LIBRARY_DOCUMENTS, "subj_id = ".$this->getDiscipline()->getId())->getItems() as $ar) {
    			$user = CStaffManager::getUser($ar->getItemValue("user_id"));
    			if (!is_null($user)) {
    				$this->_users->add($user->getId(), $user);
    			}
    		}
    	}
    	return $this->_users;
    }
    private static function getMaterialsCntByAllPersons() {
        if (is_null(self::$_materials)) {
            self::$_materials = new CArrayList();
            $query = new CQuery();
            $query->select("doc.user_id as user_id, COUNT(file.id) as cnt")
                ->from(TABLE_LIBRARY_DOCUMENTS." as doc")
                ->innerJoin(TABLE_LIBRARY_FILES." as file", "doc.nameFolder = file.nameFolder")
                ->group("doc.user_id");
            foreach ($query->execute()->getItems() as $data) {
                self::$_materials->add($data["user_id"], $data["cnt"]);
            }
        }
        return self::$_materials;
    }
    public function getMaterialsCount($key) {
		$query = new CQuery();
		$query->select("doc.user_id")
			->from(TABLE_LIBRARY_DOCUMENTS." as doc")
			->innerJoin(TABLE_LIBRARY_FILES." as file", "doc.nameFolder = file.nameFolder")
			->condition("doc.nameFolder = ".$key);
		$materialsCount = $query->execute()->getCount();
        return $materialsCount;
    }
    public function getMaterialsCountBySubject($subj_id, $user_id) {
    	$query = new CQuery();
    	$query->select("doc.user_id")
    	->from(TABLE_LIBRARY_DOCUMENTS." as doc")
    	->innerJoin(TABLE_LIBRARY_FILES." as file", "doc.nameFolder = file.nameFolder")
    	->condition("doc.subj_id = ".$subj_id." and doc.user_id = ".$user_id);
    	$materialsCount = $query->execute()->getCount();
    	return $materialsCount;
    }
    public function getFolderIds() {
        if (is_null($this->_folders)) {
            $this->_folders = new CArrayList();
            $query = new CQuery();
            $query->select("doc.nameFolder as folder, doc.user_id as user_id")
                ->from(TABLE_LIBRARY_DOCUMENTS." as doc")
                ->condition("doc.subj_id = ".$this->getDiscipline()->getId());
            foreach ($query->execute()->getItems() as $data) {
                $this->_folders->add($data["user_id"], $data["folder"]);
            }
        }
        return $this->_folders;
    }
}
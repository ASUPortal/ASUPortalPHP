<?php

class CDisciplinesController extends CFlowController{
    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление дисциплинами");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("discipline.*")
            ->from(TABLE_DISCIPLINES." as discipline");
        $disciplines = new CArrayList();
        $set->setQuery($query);
        foreach ($set->getPaginated()->getItems() as $ar) {
            $discipline = new CDiscipline($ar);
    		$disciplines->add($discipline->getId(), $discipline);
        }
        $this->addActionsMenuItem(array(
        	"title" => "Добавить",
        	"link" => "index.php?action=add",
        	"icon" => "actions/list-add.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Добавить общий для всех дисциплин учебник",
        	"link" => "index.php?action=addGeneralBook",
        	"icon" => "actions/list-add.png"
        ));
        $this->setData("paginator", $set->getPaginator());
        $this->setData("disciplines", $disciplines);
        $this->renderView("_discipline/index.tpl");
    }
    public function actionAdd() {
    	$discipline = new CDiscipline();
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=index",
    		"icon" => "actions/edit-undo.png"
    	));
    	$this->setData("discipline", $discipline);
    	$this->renderView("_discipline/add.tpl");
    }
    public function actionEdit() {
        $discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("id"));
        $this->addActionsMenuItem(array(
        	"title" => "Назад",
        	"link" => "index.php?action=index",
        	"icon" => "actions/edit-undo.png"
        ));
        $this->addActionsMenuItem(array(
        	"title" => "Удалить выделенные учебники",
        	"icon" => "actions/edit-delete.png",
        	"form" => "#Books",
        	"link" => "index.php?discipline_id=".CRequest::getInt("id"),
        	"action" => "deleteBooks"
        ));
        $this->setData("discipline", $discipline);
        $this->renderView("_discipline/edit.tpl");
    }
    public function actionDelete() {
    	$discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("id"));
    	$discipline->remove();
    	$this->redirect("index.php?action=index");
    }
    public function actionSave() {
    	$discipline = new CDiscipline();
    	$discipline->setAttributes(CRequest::getArray($discipline::getClassName()));
    	if ($discipline->validate()) {
    		$discipline->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=edit&id=".$discipline->getId());
    		} else {
    			$this->redirect("index.php?action=index");
    		}
    		return true;
    	}
    	$this->setData("discipline", $discipline);
    	$this->renderView("_discipline/edit.tpl");
    }
    public function actionAddBook() {
    	$object = new CCorriculumBook();
    	$this->setData("object", $object);
    
    	// для передачи необходимых параметров
    	$discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("discipline_id"));
    	$param = new CCorriculumDisciplineBook();
    	$param->subject_id = $discipline->getId();
    	$this->setData("param", $param);
    	/**
    	 * Генерация меню
    	*/
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=edit&id=".$discipline->getId(),
    		"icon" => "actions/edit-undo.png"
    	));
    	/**
    	 * Отображение представления
    	*/
    	$this->renderView("_discipline/_books/add.tpl");
    }
    public function actionAddGeneralBook() {
    	$object = new CCorriculumBook();
    	$this->setData("object", $object);
    
    	// для передачи необходимых параметров
    	$param = new CCorriculumDisciplineBook();
    	$param->subject_id = CDisciplinesManager::getGeneralDisciplineId("Общая дисциплина");
    	$this->setData("param", $param);
    	/**
    	 * Генерация меню
    	*/
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=index",
    		"icon" => "actions/edit-undo.png"
    	));
    	/**
    	 * Отображение представления
    	*/
    	$this->renderView("_discipline/_books/add.tpl");
    }
    public function actionEditBook() {
    	$object = CBaseManager::getCorriculumBook(CRequest::getInt("id"));
    	$this->setData("object", $object);
    
    	// для передачи необходимых параметров
    	$discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("discipline_id"));
    	$param = new CCorriculumDisciplineBook();
    	$param->subject_id = $discipline->getId();
    	$this->setData("param", $param);
    	/**
    	 * Генерация меню
    	*/
    	$this->addActionsMenuItem(array(
    		"title" => "Назад",
    		"link" => "index.php?action=edit&id=".$discipline->getId(),
    		"icon" => "actions/edit-undo.png"
    	));
    	/**
    	 * Отображение представления
    	*/
    	$this->renderView("_discipline/_books/edit.tpl");
    }
    public function actionDeleteBooks() {
    	$object = CBaseManager::getCorriculumBook(CRequest::getInt("id"));
    	$discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("discipline_id"));
    	if (!is_null($object)) {
    		$object->remove();
    	}
    	$items = CRequest::getArray("selectedInView");
    	foreach ($items as $id){
    		$object = CBaseManager::getCorriculumBook($id);
    		$object->remove();
    	}
    	$this->redirect("index.php?action=edit&id=".$discipline->getId());
    }
    public function actionSaveBook() {
    	$object = new CCorriculumBook();
    	$object->setAttributes(CRequest::getArray($object::getClassName()));
    	$param = new CCorriculumDisciplineBook();
    	$param->setAttributes(CRequest::getArray($param::getClassName()));
    	$subject_id = $param->subject_id;
    	if ($object->validate()) {
    		$object->save();
    		$disciplineBook = new CCorriculumDisciplineBook();
    		$disciplineBook->book_id = $object->getId();
    		$disciplineBook->subject_id = $subject_id;
    		$disciplineBook->save();
    		if ($this->continueEdit()) {
    			$this->redirect("index.php?action=editBook&id=".$object->getId()."&discipline_id=".$subject_id);
    		} else {
    			$this->redirect("index.php?action=edit&id=".$subject_id);
    		}
    		return true;
    	}
    	$this->setData("object", $object);
    	$this->renderView("_corriculum/_books/edit.tpl");
    }
    public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по названию
    	*/
    	$query = new CQuery();
    	$query->select("distinct(discipline.id) as id, discipline.name as name")
	    	->from(TABLE_DISCIPLINES." as discipline")
	    	->condition("discipline.name like '%".$term."%'")
	    	->limit(0, 5);
    	foreach ($query->execute()->getItems() as $item) {
    		$res[] = array(
    				"field" => "id",
    				"value" => $item["id"],
    				"label" => $item["name"],
    				"class" => "CDiscipline"
    		);
    	}
    	echo json_encode($res);
    }
    /**
     * Обновление кодов дисциплин с сайта библиотеки
     */
    public function actionUpdateLibraryCodes() {
    	// подключаем PHP Simple HTML DOM Parser
    	require_once(CORE_CWD."/_core/_external/smarty/vendor/simple_html_dom.php");

    	// подключаем библиотеку curl с указанием proxy
    	$proxy = CSettingsManager::getSettingValue("proxy_address");
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_PROXY, $proxy);
    	
    	// ссылка для загрузки дисциплин из библиотеки
    	$link = CSettingsManager::getSettingValue("link_library_disciplines");
    	
    	curl_setopt($curl, CURLOPT_URL, $link);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    	$str = curl_exec($curl);
    	curl_close($curl);
    	
    	// создаём DOM объект из строки
    	$html = str_get_html($str);

    	$result = array();
    	$arr = array();
    	if (!empty($html)) {
    		if(count($html->find(CSettingsManager::getSettingValue("disciplines_library")))) {
    			foreach ($html->find(CSettingsManager::getSettingValue("disciplines_library")) as $k=>$td) {
    				$arr[1] = $td->plaintext;
    				// коды дисциплин - последние символы после запятой из тегов a href
    				foreach ($td->find('a') as $kk=>$a) {
    					$format = explode(',', $a->href);
    					$arr[2] = end($format);
    				}
    				$result[] = $arr;
    			}
    		}
    		foreach ($result as $ar) {
    			foreach (CActiveRecordProvider::getAllFromTable(TABLE_DISCIPLINES)->getItems() as $item) {
    				$discipline = new CDiscipline($item);
    				if ($discipline->name == $ar[1]) {
    					$discipline->library_code = $ar[2];
    					$discipline->save();
    				}
    			}
    		}
    	} else {
    		$this->setData("message", "URL ".$link." не доступен, проверьте адрес прокси в настройках портала");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	}
    	
    	// очищаем память
    	$html->clear();
    	unset($html);
    	
    	$this->setData("message", "Данные добавлены успешно");
    	$this->renderView("_flow/dialog.ok.tpl", "", "");
    }
    /**
     * Добавление литературы с сайта библиотеки
     */
    public function actionAddFromUrl() {
    	foreach (CActiveRecordProvider::getAllFromTable(TABLE_DISCIPLINES)->getItems() as $item) {
    		$discipline = new CDiscipline($item);
    		if ($discipline->library_code != 0) {
    			// код дисциплины из библиотеки
    			$codeDiscipl = $discipline->library_code;
    			// id дисциплины из справочника
    			$subject_id = $discipline->getId();
    			// добавляем литературу с сайта библиотеки
    			$availability = CDisciplinesManager::addBooksFromUrl($codeDiscipl, $subject_id, true);
    			if (!$availability) {
    				// прерываем цикл foreach
    				break 1;
    				$this->setData("message", "URL не доступен");
    				$this->renderView("_flow/dialog.ok.tpl", "", "");
    			}
    		}
    	}
    	if ($availability) {
    		$this->setData("message", "Данные добавлены успешно");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	} else {
    		$this->setData("message", "URL не доступен");
    		$this->renderView("_flow/dialog.ok.tpl", "", "");
    	}
    }
    /**
     * Добавление литературы с сайта библиотеки для указанной дисциплины
     */
    public function actionAddFromUrlForDiscipline() {
    	$discipline = CDisciplinesManager::getDiscipline(CRequest::getInt("discipline_id"));
    	
    	// код дисциплины из библиотеки
    	$codeDiscipl = $discipline->library_code;
    	// id дисциплины из справочника
    	$subject_id = $discipline->getId();
    	
    	$this->setData("message", CDisciplinesManager::addBooksFromUrl($codeDiscipl, $subject_id));
    	$this->renderView("_flow/dialog.ok.tpl", "", "");
    }
}
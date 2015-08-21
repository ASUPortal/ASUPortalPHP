<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

class CPublicLibraryController extends CBaseController{
    public $allowedAnonymous = array(
        "index",
        "search",
    	"get",
    	"publicView"
    );
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
        $this->setPageTitle("Учебные материалы кафедры АСУ");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet(false);
        $set->setPageSize(10);
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("DISTINCT subject.*")
            ->from(TABLE_DISCIPLINES." as subject")
            ->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "doc.subj_id = subject.id")
            ->order("subject.name asc");
        /**
         * Последние материалы не показываем если есть
         * какой-нибудь фильтр
         */
        $showLatest = true;
        /**
         * Если есть пагинация, то тоже не стоит показывать
         * последние файлы
         */
        if (CRequest::getInt("page") !== 0) {
            $showLatest = false;
        }
        /**
         * Проверим, может есть что по фильтрам
         * Проверка фильтра по дисциплине
         */
        if (!is_null(CRequest::getFilter("subject"))) {
            $query->condition("doc.subj_id = ".CRequest::getFilter("subject"));
            $showLatest = false;
        } elseif (!is_null(CRequest::getFilter("author"))) {
            $query->condition("doc.user_id = ".CRequest::getFilter("author"));
            $showLatest = false;
        } elseif (!is_null(CRequest::getFilter("char"))) {
            $query->condition("ORD(LEFT(subject.name, 1)) = ".CRequest::getFilter("char"));
            $showLatest = false;
        }
        if (CSession::isAuth() and (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY or
        		CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL)) {
        			$this->addActionsMenuItem(array(
        				array(
        					"title" => "Просмотр своих предметов/файлов",
        					"link" => WEB_ROOT."_modules/_library/index.php?action=view&filter=author:".CSession::getCurrentUser()->getId(),
        					"icon" => "actions/edit-find-replace.png"
        			)
        		)
        	);
        }
        $folders = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $folder = new CLibraryFolder(new CTerm($ar));
            $folders->add($folders->getCount(), $folder);
        }
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->setData("folders", $folders);
        $this->setData("paginator", $set->getPaginator());
        $this->setData("showLatest", $showLatest);
        $this->renderView("_library/public/public.index.tpl");
    }
    public function actionGet() {
        $file = CLibraryManager::getFile(CRequest::getInt("id"));
        if (!is_null($file)) {
            $this->redirect($file->getFileDownloadLink());
        }
    }
    public function actionView() {
    	$set = new CRecordSet(false);
    	$query = new CQuery();
    	$set->setQuery($query);
    	$query->select("DISTINCT subject.*")
	    	->from(TABLE_DISCIPLINES." as subject")
	    	->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "doc.subj_id = subject.id")
	    	->condition("doc.user_id = ".CRequest::getFilter("author"))
	    	->order("subject.name asc");
    	$selectedUser = null;
    	$usersQuery = new CQuery();
    	$usersQuery->select("user.*")
	    	->from(TABLE_USERS." as user")
	    	->order("user.fio asc")
	    	->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "user.id = doc.user_id");
    	// фильтр по автору
    	if (!is_null(CRequest::getFilter("author"))) {
    		$selectedUser = CRequest::getFilter("author");
    		$author = CRequest::getFilter("author");
    	} else {
    		$query->condition("doc.user_id = ".CSession::getCurrentUser()->getId());
    		$author = CSession::getCurrentUser()->getId();
    	}
    	$users = array();
    	foreach ($usersQuery->execute()->getItems() as $ar) {
    		$user = new CUser(new CActiveRecord($ar));
    		$users[$user->getId()] = $user->getName();
    	}
    	$folders = new CArrayList();
    	foreach ($set->getPaginated()->getItems() as $ar) {
    		$folder = new CLibraryFolder(new CTerm($ar));
    		$folders->add($folders->getCount(), $folder);
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_library/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	if (CSession::isAuth() and (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY or
    			CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL)) {
    				$this->addActionsMenuItem(array(
    					array(
    						"title" => "Добавить предмет",
    						"link" => WEB_ROOT."_modules/_library/index.php?action=addDocument&filter=author:".$author,
    						"icon" => "actions/list-add.png"
    					)
    			)
    		);
    	}
    	$this->addCSSInclude(JQUERY_UI_CSS_PATH);
    	$this->addJSInclude(JQUERY_UI_JS_PATH);
    	$this->setData("folders", $folders);
    	$this->setData("users", $users);
    	$this->setData("selectedUser", $selectedUser);
    	$this->setData("author", $author);
    	$this->setData("paginator", $set->getPaginator());
    	$this->renderView("_library/view.tpl");
    }
    public function actionPublicView() {
    	$files = CLibraryManager::getFilesByFolder(CRequest::getInt("id"));
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_library/index.php",
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("files", $files);
    	$this->renderView("_library/public/public.view.tpl");
    }
    public function actionAddDocument() {
    	$query = new CQuery();
    	$query->select("doc.*")
	    	->from(TABLE_LIBRARY_DOCUMENTS." as doc");
    	foreach ($query->execute()->getItems() as $ar) {
    		$document = new CLibraryDocument(new CActiveRecord($ar));
    		$nameFolder=0;
    		if ($document->nameFolder > $nameFolder) {
    			$nameFolder=$document->nameFolder;
    		}
    		$nameFolder++;
    	}
    	$document = new CLibraryDocument();
    	if (!is_null(CRequest::getFilter("author"))) {
    		$document->user_id = CRequest::getFilter("author");
    		$author = CRequest::getFilter("author");
    	} else {
    		$document->user_id = CSession::getCurrentUser()->getId();
    		$author = CSession::getCurrentUser()->getId();
    	}
    	$document->nameFolder = $nameFolder;
    	// отбор дисциплин, которых нет у пользователя
    	$disciplines = array();
    	$query = new CQuery();
    	$query->select("distinct(discipline.id) as id, discipline.name as name")
	    	->from(TABLE_DISCIPLINES." as discipline")
	    	->leftJoin(TABLE_LIBRARY_DOCUMENTS." as document", "discipline.id = document.subj_id")
	    	->condition("discipline.id not in (select `subj_id` from `documents` where `user_id`=".$author.")");
    	foreach ($query->execute()->getItems() as $item) {
    		$disciplines[$item["id"]] = $item["name"];
    	}
		$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_library/index.php?action=view",
				"icon" => "actions/edit-undo.png"
			),
			array(
				"title" => "Добавить новую дисциплину",
				"link" => WEB_ROOT."_modules/_taxonomy/index.php?action=legacy&id=10",
				"icon" => "actions/list-add.png"
			)
    	));
    	$this->setData("document", $document);
    	$this->setData("disciplines", $disciplines);
    	$this->renderView("_library/addDocument.tpl");
    }
    public function actionDeleteDocument() {
    	$document = CLibraryManager::getDocument(CRequest::getInt("id"));
    	if (!is_null($document)) {
    		$directory = CORE_CWD.CORE_DS."library".CORE_DS.$document->nameFolder.CORE_DS;
    		$handle = opendir($directory);
    		while (false !== ($file = readdir($handle))) {
    			if (is_file($directory.CORE_DS.$file)) {
    				unlink($directory.CORE_DS.$file);
    			}
    		}
    		closedir($handle);
    		rmdir($directory);
    		$document->remove();
    	}
    	$this->redirect("?action=view");
    }
    public function actionEditDocument() {
    	$document = CLibraryManager::getDocument(CRequest::getInt("id"));
    	if (!is_null(CRequest::getFilter("author"))) {
    		$author = CRequest::getFilter("author");
    	} else {
    		$author = CSession::getCurrentUser()->getId();
    	}
    	// отбор дисциплин, которых нет у пользователя
    	$disciplines = array();
    	$query = new CQuery();
    	$query->select("distinct(discipline.id) as id, discipline.name as name")
	    	->from(TABLE_DISCIPLINES." as discipline")
	    	->leftJoin(TABLE_LIBRARY_DOCUMENTS." as document", "discipline.id = document.subj_id")
	    	->condition("discipline.id not in (select `subj_id` from `documents` where `user_id`=".$author." and `id`!=".CRequest::getInt("id").")");
    	foreach ($query->execute()->getItems() as $item) {
    		$disciplines[$item["id"]] = $item["name"];
    	}
		$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_library/index.php?action=view&filter=author:".$author,
				"icon" => "actions/edit-undo.png"
			)
    	));
    	$this->setData("document", $document);
    	$this->setData("author", $author);
    	$this->setData("disciplines", $disciplines);
    	$this->renderView("_library/editDocument.tpl");
    }
    public function actionSaveDocument() {
    	$document = new CLibraryDocument();
    	$document->setAttributes(CRequest::getArray($document::getClassName()));
    	if ($document->validate()) {
    		$document->save();
    		mkdir(CORE_CWD.CORE_DS."library/".$document->nameFolder, 0777);
    		if ($this->continueEdit()) {
    			$this->redirect("?action=editDocument&id=".$document->getId());
    		} else {
    			$this->redirect("index.php?action=view");
    		}
    		return true;
    	} else {
    		$this->redirect("index.php?action=addDocument");
    	}
    	$this->setData("document", $document);
    	$this->renderView("_library/editDocument.tpl");
    }
    public function actionViewFiles() {
    	$files = CLibraryManager::getFilesByFolder(CRequest::getInt("id"));
    	if (!is_null(CRequest::getFilter("author"))) {
    		$author = CRequest::getFilter("author");
    	} else {
    		$author = CSession::getCurrentUser()->getId();
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_library/index.php?action=view&filter=author:".CRequest::getFilter("author"),
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	if (CSession::isAuth() and (CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_OWN_ONLY or
    			CSession::getCurrentUser()->getLevelForCurrentTask() == ACCESS_LEVEL_WRITE_ALL)) {
    				$this->addActionsMenuItem(array(
    					array(
    						"title" => "Добавить файл",
    						"link" => WEB_ROOT."_modules/_library/index.php?action=addFile&id=".CRequest::getInt("id")."&filter=author:".CRequest::getFilter("author"),
    						"icon" => "actions/list-add.png"
    				)
    			)
    		);
    	}
    	$this->setData("files", $files);
    	$this->renderView("_library/viewFiles.tpl");
    }
    public function actionAddFile() {
    	$file = new CLibraryFile();
    	if (!is_null(CRequest::getFilter("author"))) {
    		$file->user_id = CRequest::getFilter("author");
    		$author = CRequest::getFilter("author");
    	} else {
    		$file->user_id = CSession::getCurrentUser()->getId();
    		$author = CSession::getCurrentUser()->getId();
    	}
    	$file->nameFolder = CRequest::getInt("id");
    	$file->date_time = date("Y-m-d H:i:s");
		$this->addActionsMenuItem(array(
			array(
				"title" => "Назад",
				"link" => WEB_ROOT."_modules/_library/index.php?action=viewFiles&id=".CRequest::getInt("id")."&filter=author:".$author,
				"icon" => "actions/edit-undo.png"
			)
    	));
    	$this->setData("file", $file);
    	$this->renderView("_library/addFile.tpl");
    }
    public function actionDeleteFile() {
    	$file = CLibraryManager::getFile(CRequest::getInt("id_file"));
    	if (!is_null($file)) {
    		$directory = CORE_CWD.CORE_DS."library".CORE_DS.$file->nameFolder.CORE_DS.$file->nameFile;
			unlink($directory);
    		$file->remove();
    	}
    	$this->redirect("?action=viewFiles&id=".CRequest::getInt("id"));
    }
    public function actionEditFile() {
    	$file = CLibraryManager::getFile(CRequest::getInt("id_file"));
    	if (!is_null(CRequest::getFilter("author"))) {
    		$author = CRequest::getFilter("author");
    	} else {
    		$author = CSession::getCurrentUser()->getId();
    	}
    	$this->addActionsMenuItem(array(
    		array(
    			"title" => "Назад",
    			"link" => WEB_ROOT."_modules/_library/index.php?action=viewFiles&id=".$file->nameFolder."&filter=author:".$author,
    			"icon" => "actions/edit-undo.png"
    		)
    	));
    	$this->setData("file", $file);
    	$this->renderView("_library/editFile.tpl");
    }
    public function actionSaveFile() {
    	$file = new CLibraryFile();
    	$file->setAttributes(CRequest::getArray($file::getClassName()));
    	if ($file->validate()) {
    		$file->save();
    		if ($this->continueEdit()) {
    			$this->redirect("?action=editFile&id=".$file->nameFolder."&id_file=".$file->getId()."&filter=author:".$file->user_id);
    		} else {
    			$this->redirect("index.php?action=viewFiles&id=".$file->nameFolder."&filter=author:".$file->user_id);
    		}
    		return true;
    	}
    	$this->setData("file", $file);
    	$this->renderView("_library/editFile.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("query");
        /**
         * Поиск по названию дисциплины
         */
        $query = new CQuery();
        $query->select("distinct(subject.id) as id, subject.name as name")
            ->from(TABLE_DISCIPLINES." as subject")
            ->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "doc.subj_id = subject.id")
            ->condition("subject.name like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 1
            );
        }
        /**
         * По фамилии преподавателя
         */
        $query = new CQuery();
        $query->select("distinct(user.id) as id, person.fio as name")
            ->from(TABLE_PERSON." as person")
            ->condition("person.fio like '%".$term."%'")
            ->innerJoin(TABLE_USERS." as user", "person.id = user.kadri_id")
            ->innerJoin(TABLE_LIBRARY_DOCUMENTS." as doc", "doc.user_id = user.id")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 2
            );
        }
        /**
         * По файлу
         */
        $query = new CQuery();
        $query->select("distinct(file.nameFolder) as id, file.browserFile as name")
            ->from(TABLE_LIBRARY_FILES." as file")
            ->condition("file.browserFile like '%".$term."%'")
            ->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $res[] = array(
                "label" => $item["name"],
                "value" => $item["name"],
                "object_id" => $item["id"],
                "type" => 3
            );
        }
        echo json_encode($res);
    }
}
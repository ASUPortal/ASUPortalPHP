<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 31.03.13
 * Time: 17:02
 * To change this template use File | Settings | File Templates.
 */

class CPublicLibraryController extends CBaseController{
    private $allowedAnonymous = array(
        "index",
        "search"
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
        $set = new CRecordSet();
        $set->setPageSize(5);
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
        $this->renderView("_library/public.index.tpl");
    }
    public function actionGet() {
        $file = CLibraryManager::getFile(CRequest::getInt("id"));
        if (!is_null($file)) {
            $this->redirect($file->getFileDownloadLink());
        }
    }
    public function actionView() {
        $files = CLibraryManager::getFilesByFolder(CRequest::getInt("id"));
        $this->setData("files", $files);
        $this->renderView("_library/public.view.tpl");
    }
    public function actionSearch() {
        $res = array();
        $term = CRequest::getString("term");
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
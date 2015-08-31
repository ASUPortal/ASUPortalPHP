<?php
class CGroupDashboardController extends CBaseController{
    protected $_isComponent = true;

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
        $this->setPageTitle("Рабочий стол группы");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_DASHBOARD." as t")
            ->condition("group_id=".CRequest::getInt("id")." AND parent_id=0")
            ->order("t.id asc");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CDashboardItem($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить",
            "link" => "groupdashboard.php?action=add",
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_users/groupdashboard/index.tpl");
    }
    public function actionAdd() {
        $object = new CDashboardItem();
        $object->group_id = CRequest::getInt("id");
        $this->setData("object", $object);
        /**
         * Соберем необходимые иконки
         */
        $icons = new CArrayList();
        $iconSources = new CArrayList();
        $dirs = array(
            "actions",
            "apps",
            "categories",
            "devices",
            "emblems",
            "emotes",
            "mimetypes",
            "places",
            "status"
        );
        foreach ($dirs as $dir) {
            if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
                while ($file = readdir($h)) {
                    if (strpos($file, ".png")) {
                        $iconSources->add($dir."/".$file, $dir."/".$file);
                    }
                }
                closedir($h);
            }
        }
        /**
         * Теперь исключаем те, которых нет в фаензе
         */
        foreach ($dirs as $dir) {
            if (file_exists(CORE_CWD."/images/tango/64x64/".$dir)) {
                if ($h = opendir(CORE_CWD."/images/tango/64x64/".$dir."/")) {
                    while ($file = readdir($h)) {
                        if ($iconSources->hasElement($dir."/".$file)) {
                            $icons->add($dir."/".$file, $dir."/".$file);
                        }
                    }
                    closedir($h);
                }
            }
        }
        /**
         * Доступные родительские элементы
         */
        $parents = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "group_id = ".CRequest::getInt("id")." and parent_id = 0")->getItems() as $ar) {
            $item = new CDashboardItem($ar);
            $parents->add($item->getId(), $item->title);
            foreach ($item->children->getItems() as $child) {
                $parents->add($child->getId(), " - ".$child->title);
            }
        }
        $this->setData("parents", $parents);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "groupdashboard.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->setData("icons", $icons);
        $this->renderView("_users/groupdashboard/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getDashboardItem(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Соберем необходимые иконки
         */
        $icons = new CArrayList();
        $iconSources = new CArrayList();
        $dirs = array(
            "actions",
            "apps",
            "categories",
            "devices",
            "emblems",
            "emotes",
            "mimetypes",
            "places",
            "status"
        );
        foreach ($dirs as $dir) {
            if ($h = opendir(CORE_CWD."/images/tango/16x16/".$dir."/")) {
                while ($file = readdir($h)) {
                    if (strpos($file, ".png")) {
                        $iconSources->add($dir."/".$file, $dir."/".$file);
                    }
                }
                closedir($h);
            }
        }
        $this->setData("icons", $icons);
        /**
         * Доступные родительские элементы
         */
        $parents = new CArrayList();
        foreach (CActiveRecordProvider::getWithCondition(TABLE_DASHBOARD, "group_id = ".$object->group_id." and parent_id = 0")->getItems() as $ar) {
            $item = new CDashboardItem($ar);
            $parents->add($item->getId(), $item->title);
            foreach ($item->children->getItems() as $child) {
                $parents->add($child->getId(), " - ".$child->title);
            }
        }
        $this->setData("parents", $parents);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "groupdashboard.php?action=index",
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_users/groupdashboard/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getDashboardItem(CRequest::getInt("id"));
        $group = $object->group_id;
        $object->remove();
        $this->redirect("groupdashboard.php?action=index&id=".$group);
    }
    public function actionSave() {
        $object = new CDashboardItem();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("groupdashboard.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("groupdashboard.php?action=index&id=".$object->group_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_users/groupdashboard/edit.tpl");
    }
}
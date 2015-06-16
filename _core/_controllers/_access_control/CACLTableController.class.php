<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 04.01.13
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */
class CACLTableController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление таблицами доступа");

        parent::__construct();
    }
    public function actionIndex() {
        $tables = new CArrayList();
        $set = CActiveRecordProvider::get2AllFromTable(TABLE_ACL_TABLES);
        foreach ($set->getPaginated()->getItems() as $item) {
            $table = new CACLTable($item);
            $tables->add($table->getId(), $table);
        }
        $this->setData("tables", $tables);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_acl_manager/tables/index.tpl");
    }
    public function actionAdd() {
        $table = new CACLTable();
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/dialogs/personSelector.js");
        $this->setData("table", $table);
        $this->renderView("_acl_manager/tables/add.tpl");
    }
    public function actionEdit() {
        $table = new CACLTable();
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/dialogs/personSelector.js");
        $table = CACLManager::getACLTable(CRequest::getInt("id"));
        $this->setData("table", $table);
        $this->renderView("_acl_manager/tables/edit.tpl");
    }
    public function actionSave() {
        $table = new CACLTable();
        $table->setAttributes(CRequest::getArray($table::getClassName()));
        if ($table->validate()) {
            $transaction = new CTransaction();
            $table->save();
            $this->createACLTables($table);
            $this->updateDefaultAccess($table, CRequest::getArray("default_readers"), 1);
            $this->updateDefaultAccess($table, CRequest::getArray("default_authors"), 2);
            $transaction->commit();
            $this->redirect("?action=index");
            return true;
        }
        $this->addJSInclude("_core/jquery-ui-1.8.20.custom.min.js");
        $this->addCSSInclude("_core/jUI/jquery-ui-1.8.2.custom.css");
        $this->addJSInclude("_core/dialogs/personSelector.js");
        $this->setData("table", $table);
        $this->renderView("_acl_manager/tables/edit.tpl");
    }
    public function actionDelete() {
        $table = CACLManager::getACLTable(CRequest::getInt("id"));
        $table->remove();
        $this->redirect("?action=index");
    }

    /**
     * Обслуживание таблиц доступа. Выполняется обновление читателей
     * и редакторов в соответствии со значениями по умолчанию
     */
    public function actionService() {
        $start = CRequest::getInt("start");
        $table = CACLManager::getACLTable(CRequest::getInt("id"));
        // будем работать простыми запросами чтобы память не заканчивалась и все не падало
        $transaction = new CTransaction();
        $res = mysql_query("SELECT * FROM ".$table->table." LIMIT ".$start.", ".($start + 1000)) or die(mysql_error());
        if (mysql_num_rows($res) == 0) {
            $this->redirect("?action=index");
        }
        while ($row = mysql_fetch_assoc($res)) {
            $ar = new CActiveRecord($row);
            $model = new CActiveModel($ar);
            $model->getRecord()->setTable($table->table);
            $model->setReaders($table->getDefaultReaders());
            $model->setAuthors($table->getDefaultAuthors());
            $model->saveACLEntries();
        }
        $table->last_service = time();
        $table->save();
        $transaction->commit();
        echo "Processing...";
        echo '
        <script>
            window.location.href="'.WEB_ROOT.'_modules/_acl_manager/tables.php?action=service&id='.$table->getId().'&start='.($start + 1000).'";
        </script>
        ';
    }

    /**
     * Создает таблицы доступа. Вынес чтобы метод контроллера толще не становился,
     * а то и так уже читать сложно
     *
     * @param CActiveModel $table
     */
    private function createACLTables(CActiveModel $table) {
        // создаем дополнительные таблицы если их еще нет
        $query = new C2Query();
        $query->query("
            CREATE TABLE IF NOT EXISTS `".$table->table.ACL_ENTRIES."` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `object_id` int(11) NOT NULL,
              `entry_type` int(11) NOT NULL,
              `entry_id` int(11) NOT NULL,
              `level` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `object_id` (`object_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Объекты доступа для таблицы ".$table->title."' AUTO_INCREMENT=1 ;
            ")->execute();
        $query = new C2Query();
        $query->query("
            CREATE TABLE IF NOT EXISTS `".$table->table.ACL_USERS."` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `object_id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `level` int(11) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `object_id` (`object_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Доступ пользователей к записям таблицы ".$table->title."' AUTO_INCREMENT=1 ;
            ")->execute();
        /*
         * почему-то не работает
        $query = new C2Query();
        $query->query("
        ALTER TABLE  `".$table->table.ACL_ENTRIES."` ADD FOREIGN KEY (  `object_id` ) REFERENCES  `".$table->table."` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;
        ALTER TABLE  `".$table->table.ACL_USERS."` ADD FOREIGN KEY (  `object_id` ) REFERENCES  `".$table->table."` (`id`) ON DELETE CASCADE ON UPDATE CASCADE ;
        ")->execute();
        */
    }

    /**
     * Обновление прав доступа к таблице по умолчанию
     *
     * @param CActiveModel $model
     * @param array $entries
     * @param int $level
     */
    private function updateDefaultAccess(CActiveModel $model, array $entries, $level = 1) {
        // 1. удаляем старые записи доступа
        foreach (CActiveRecordProvider::getWithCondition(TABLE_ACL_DEFAULTS, "table_id=".$model->getId())->getItems() as $item) {
            $obj = new CActiveModel($item);
            $obj->remove();
        }
        // 2. создаем новые записи уровня сущностей
        foreach ($entries["id"] as $key=>$value) {
            $entry = new CActiveModel();
            $entry->getRecord()->setTable(TABLE_ACL_DEFAULTS);
            $entry->table_id = $model->getId();
            $entry->level = $level;
            $entry->entry_type = $entries["type"][$key];
            $entry->entry_id = $value;
            $entry->save();
        }
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 13.03.15
 * Time: 22:26
 */

class CJsonController extends CBaseController{
    public function __construct() {
        // если не определен параметр type=json, то ведем себя
        // как обычный контроллер
        if (CRequest::getString("type") == "") {
            parent::__construct();
            return;
        }
        if (CRequest::getString("type") != "json") {
            parent::__construct();
            return;
        }
        if (CRequest::getString("type") == "json") {
            $this->processRequest();
        }
    }

    /**
     * Функция выполнения запроса
     */
    private function processRequest() {
        if (CRequest::getString("action") == "get") {
            $this->actionJSONGet();
        } else {
            echo "Действие ".CRequest::getString("action")." не реализовано";
        }
    }

    /**
     * Получение данных модели по идентификатору
     */
    private function actionJSONGet() {
        // получим название класса модели, которую надо пользователю
        $modelClass = CRequest::getString("model");
        // идентификатор
        $id = CRequest::getInt("id");
        // создадим объект, посмотрим, в какой таблице он весь живет
        /**
         * @var $model CActiveModel
         */
        $model = new $modelClass();
        // проверим, может не реализует нужный интерфейс
        if (!is_a($model, "IJSONSerializable")) {
            throw new Exception("Класс ".$modelClass." не реализует интерфейс IJSONSerializable");
        }
        $modelTable = $model->getRecord()->getTable();
        // получим из этой таблицы объект
        $ar = CActiveRecordProvider::getById($modelTable, $id);
        if (is_null($ar)) {
            echo json_encode(null);
            return;
        }
        // создадим новый объект указанного типа
        /**
         * @var $model IJSONSerializable
         */
        $model = new $modelClass($ar);
        // получим json объект модели
        $jsonObj = $model->toJsonObject();
        // добавим к json-объекту перевод
        $jsonObj->_translation = CCoreObjectsManager::getAttributeLabels($model);
        // сразу добавим штатную валидацию
        // может, чуть позже, пока нет
        echo json_encode($jsonObj);
    }
}
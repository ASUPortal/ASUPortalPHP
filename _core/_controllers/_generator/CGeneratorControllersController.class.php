<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.08.13
 * Time: 12:30
 * To change this template use File | Settings | File Templates.
 */

class CGeneratorControllersController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Генератор контроллеров");

        parent::__construct();
    }
    public function actionAdd() {
        $controller = new CGeneratableController();
        $this->setData("controller", $controller);
        $this->renderView("__generator/controller/add.tpl");
    }
    public function actionGenerate() {
        $params = new CGeneratableController();
        $params->setAttributes(CRequest::getArray($params::getClassName()));
        if ($params->validate()) {
            $files = array();

            /**
             * Создаем сам контроллер
             */
            $templateController = file_get_contents(CORE_CWD."/_core/_models/generator/templates/controller.tpl");
            $controllerFields = array(
                "controllerName" => $params->controllerName,
                "controllerFile" => CUtils::strRightBack($params->controllerFile, CORE_DS),
                "controllerPath" => $params->controllerPath,
                "pageTitle" => $params->pageTitle,
                "modelName" => $params->modelName,
                "modelTable" => $params->modelTable,
                "modelManager" => $params->modelManager,
                "modelManagerGetter" => $params->modelManagerGetter,
                "viewPath" => $params->viewPath
            );
            foreach ($controllerFields as $key=>$value) {
                $templateController = str_replace("#".$key."#", $value, $templateController);
            }
            $filePath = CORE_CWD.CORE_DS.$params->controllerPath.$params->controllerName.".class.php";
            CUtils::createFoldersToPath(CUtils::strLeftBack($filePath, CORE_DS));
            file_put_contents($filePath, $templateController);
            $files[] = $filePath;

            /**
             * Создаем модуль, которым этот контроллер рулит
             */
            $templateModule = file_get_contents(CORE_CWD."/_core/_models/generator/templates/module.tpl");
            $moduleFields = array(
                "controllerName" => $params->controllerName
            );
            foreach ($moduleFields as $key=>$value) {
                $templateModule = str_replace("#".$key."#", $value, $templateModule);
            }
            $filePath = CORE_CWD.CORE_DS.$params->controllerFile;
            CUtils::createFoldersToPath(CUtils::strLeftBack($filePath, CORE_DS));
            file_put_contents($filePath, $templateModule);
            $files[] = $filePath;

            /**
             * Модель данных, тоже пусть будет для полноты картины
             */
            if ($params->modelGenerate == 1) {
                $templateModel = file_get_contents(CORE_CWD."/_core/_models/generator/templates/model.tpl");
                $modelFields = array(
                    "modelName" => $params->modelName,
                    "modelTable" => $params->modelTable
                );
                foreach ($modelFields as $key=>$value) {
                    $templateModel = str_replace("#".$key."#", $value, $templateModel);
                }
                $filePath = CORE_CWD.CORE_DS.$params->modelPath.$params->modelName.".class.php";
                CUtils::createFoldersToPath(CUtils::strLeftBack($filePath, CORE_DS));
                file_put_contents($filePath, $templateModel);
                $files[] = $filePath;
            }

            /**
             * Получим список полей модели, которые хранятся в БД
             * и из них сразу наклепаем столбцов в таблице и полей в форме
             */
            $fields = array();
            $modelName = $params->modelName;
            $model = new $modelName();
            foreach ($model->getDbTableFields()->getItems() as $field) {
                $fields[] = $field->name;
            }
            $viewFormFields = array();
            $viewTableHeadFields = array();
            $viewTableBodyFields = array();
            foreach ($fields as $field) {
                $templateField = file_get_contents(CORE_CWD."/_core/_models/generator/templates/field.text.tpl");
                $fieldFields = array(
                    "fieldTitle" => $field
                );
                foreach ($fieldFields as $key=>$value) {
                    $templateField = str_replace("#".$key."#", $value, $templateField);
                }
                $viewFormFields[] = $templateField;

                $templateField = file_get_contents(CORE_CWD."/_core/_models/generator/templates/field.tablebody.tpl");
                $fieldFields = array(
                    "fieldTitle" => $field
                );
                foreach ($fieldFields as $key=>$value) {
                    $templateField = str_replace("#".$key."#", $value, $templateField);
                }
                $viewTableBodyFields[] = $templateField;

                $templateField = file_get_contents(CORE_CWD."/_core/_models/generator/templates/field.tablehead.tpl");
                $fieldFields = array(
                    "fieldTitle" => $field
                );
                foreach ($fieldFields as $key=>$value) {
                    $templateField = str_replace("#".$key."#", $value, $templateField);
                }
                $viewTableHeadFields[] = $templateField;
            }
            $viewFormFields = implode("\n\n", $viewFormFields);
            $viewTableHeadFields = implode("\n", $viewTableHeadFields);
            $viewTableBodyFields = implode("\n", $viewTableBodyFields);

            /**
             * Набор представлений на все случаи жизни
             */
            CUtils::createFoldersToPath(CORE_CWD.CORE_DS."_core".CORE_DS."_views".CORE_DS.$params->viewPath);
            $viewFiles = array(
                CORE_CWD."/_core/_models/generator/templates/index.tpl",
                CORE_CWD."/_core/_models/generator/templates/index.right.tpl",
                CORE_CWD."/_core/_models/generator/templates/add.tpl",
                CORE_CWD."/_core/_models/generator/templates/add.right.tpl",
                CORE_CWD."/_core/_models/generator/templates/edit.tpl",
                CORE_CWD."/_core/_models/generator/templates/edit.right.tpl",
                CORE_CWD."/_core/_models/generator/templates/form.tpl",
            );
            $viewFields = array(
                "viewIndexTitle" => $params->viewIndexTitle,
                "viewIndexNoObjects" => $params->viewIndexNoObjects,
                "viewObjectSingleName" => $params->viewObjectSingleName,
                "viewObjectSingleNameRP" => $params->viewObjectSingleNameRP,
                "controllerFile" => CUtils::strRightBack($params->controllerFile, CORE_DS),
                "viewPath" => $params->viewPath,
                "viewFormFields" => $viewFormFields,
                "viewTableHeadFields" => $viewTableHeadFields,
                "viewTableBodyFields" => $viewTableBodyFields
            );
            foreach ($viewFiles as $viewFile) {
                $templateView = file_get_contents($viewFile);
                foreach ($viewFields as $key=>$value) {
                    $templateView = str_replace("#".$key."#", $value, $templateView);
                }
                $filePath = CORE_CWD.CORE_DS."_core".CORE_DS."_views".CORE_DS.$params->viewPath.CORE_DS.CUtils::strRightBack($viewFile, CORE_DS);
                file_put_contents($filePath, $templateView);
                $files[] = $filePath;
            }
            $this->setData("files", $files);
            $this->renderView("__generator/controller/success.tpl");
            return true;
        }
        $this->setData("controller", $params);
        $this->renderView("__generator/controller/add.tpl");
    }
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 23.01.14
 * Time: 20:58
 * To change this template use File | Settings | File Templates.
 */

class CUploadController extends CBaseController {
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
        $this->setPageTitle("Системный поиск");

        parent::__construct();
    }

    /**
     * Загрузка файлов на сервер
     */
    public function actionUploadFile() {
        $field = $_POST["_watch"];
        $uploadTo = $_POST["_storage"];

        if (array_key_exists($field, $_FILES)) {
            $filename = md5(time()).".".CUtils::strRightBack($_FILES[$field]["name"], ".");
            CUtils::createFoldersToPath($uploadTo);
            move_uploaded_file($_FILES[$field]["tmp_name"], $uploadTo.$filename);
            echo $filename;
        }
    }

    /**
     * Получение информации о файле
     */
    public function actionGetInfo() {
        $storage = $_POST["_storage"];
        $file = $_POST["_file"];
        $size = $_POST["_size"];
        $index = $_POST["_index"];

        $result = array(
            "isImage" => false,
            "previewUrl" => "",
            "fullUrl" => "",
            "name" => $file,
            "index" => $index
        );

        if (file_exists($storage.$file)) {
            $result["fullUrl"] = WEB_ROOT.ROOT_FOLDER.CUtils::strRight($storage, CORE_CWD).$file;
            $result["isImage"] = CUtils::isImage($storage.$file);
            if (CUtils::isImage($storage.$file)) {
                $result["previewUrl"] = WEB_ROOT.ROOT_FOLDER."_modules/_thumbnails/?src=".$result["fullUrl"]."&w=".$size;
            } else {

            }
        }

        echo json_encode($result);
    }
}
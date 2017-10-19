<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 10.03.13
 * Time: 17:45
 * To change this template use File | Settings | File Templates.
 */
class CZipController extends CBaseController {
	public $allowedAnonymous = array(
		"archive"
	);
	
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Подсистема архивирования");

        parent::__construct();
    }
    public function actionArchive() {
        $files = CRequest::getArray("files");
        $zip = new ZipArchive();
        $filename = date("dmY_Hns").".zip";
        $zip->open(ZIP_DOCUMENTS_DIR.$filename, ZipArchive::CREATE);
        foreach ($files as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        if (CRequest::getInt("noredirect") == "1") {
            echo json_encode(array(
                "filename" => ZIP_DOCUMENTS_DIR.$filename,
                "url" => ZIP_DOCUMENTS_URL.$filename
            ));
        } else {
            $this->redirect(ZIP_DOCUMENTS_URL.$filename);
        }
    }
}

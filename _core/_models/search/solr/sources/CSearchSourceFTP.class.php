<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:24
 */

class CSearchSourceFTP extends CComponent implements ISearchSource {
    public $server = "";
    public $login = "";
    public $password = "";
    public $id;

    protected function init() {
        $this->server = CSettingsManager::getSetting($this->server);
        $this->login = CSettingsManager::getSetting($this->login);
        $this->password = CSettingsManager::getSetting($this->password);
    }


    public function getFilesToIndex() {
        return array();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFile(CSearchFile $fileDescriptor)
    {
        // TODO: Implement getFile() method.
    }


}
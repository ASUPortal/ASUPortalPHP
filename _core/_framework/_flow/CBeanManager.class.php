<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Aleksandr Barmin
 * Date: 12.10.14
 * Time: 18:21
 * 
 * URL: http://mydesignstudio.ru/
 * mailto: abarmin@mydesignstudio.ru
 * twitter: @alexbarmin
 */

class CBeanManager extends CComponent{
    protected $cacheDir = "";

    /**
     * Получить бин с диска
     *
     * @param $id
     * @return mixed
     */
    public function getStatefullBean($id) {
        if (is_a(CApp::getApp()->getApp(), "CCacheDummy")) {
            if (file_exists($this->cacheDir.$id)) {
                $obj = unserialize(file_get_contents($this->cacheDir.$id));
                return $obj;
            }
        } else {
            return CApp::getApp()->cache->get("bean_".$id);
        }
    }

    /**
     * Сериализовать бин на диск
     *
     * @param CStatefullBean $bean
     */
    public function serializeBean(CStatefullBean $bean) {
        if (is_a(CApp::getApp()->getApp(), "CCacheDummy")) {
            if (file_exists($this->cacheDir.$bean->getBeanId())) {
                unlink($this->cacheDir.$bean->getBeanId());
            }
            file_put_contents($this->cacheDir.$bean->getBeanId(), serialize($bean));
        } else {
            CApp::getApp()->cache->set("bean_".$bean->getBeanId(), $bean);
        }
    }
}
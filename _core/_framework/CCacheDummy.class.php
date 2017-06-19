<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:27
 * To change this template use File | Settings | File Templates.
 */
class CCacheDummy extends CCache{
    private static $_cache = null;

    private function getCache() {
        if (is_null(CCacheDummy::$_cache)) {
            CCacheDummy::$_cache = new CArrayList();
        }
        return CCacheDummy::$_cache;
    }
    public function get($key) {
        return $this->getCache()->getItem($key);
    }
    public function set($key, $value, $expire = 0) {
       $this->getCache()->add($key, $value);
    }
    public function hasCache($key) {
        return $this->getCache()->hasElement($key);
    }
    public function delete($key) {
        return $this->getCache() = new CArrayList();
    }
}

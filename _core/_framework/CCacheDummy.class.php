<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:27
 * To change this template use File | Settings | File Templates.
 */
class CCacheDummy extends CCache{
    private $_cache = null;
    private function getCache() {
        if (is_null($this->_cache)) {
            $this->_cache = new CArrayList();
        }
        return $this->_cache;
    }
    public function get($key) {
        return $this->getCache()->getItem($key);
    }
    public function set($key, $value) {
       $this->getCache()->add($key, $value);
    }
    public function hasCache($key) {
        return $this->getCache()->hasElement($key);
    }
}

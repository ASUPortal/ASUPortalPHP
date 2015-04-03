<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 16:00
 */

class CCacheAPC extends CCache{
    protected $timeout;

    public function get($id) {
        if ($this->hasCache($id)) {
            return apc_fetch($id);
        }
        return null;
    }

    public function set($id, $value, $expire = null){
        if (is_null($expire)) {
            $expire = $this->timeout;
        }
        apc_store($id, $value, $expire);
    }

    public function hasCache($key) {
        return apc_exists($key);
    }

}
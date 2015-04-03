<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 03.04.15
 * Time: 16:01
 */

interface ICache {
    public function get($id);
    public function set($id, $value, $expire = 0);
    public function hasCache($key);
}
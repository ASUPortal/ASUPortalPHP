<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */
abstract class CCache {
    public function get($key){}
    public function set($key, $value){}
    public function hasCache($key){}
}

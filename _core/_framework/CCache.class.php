<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 16:26
 * To change this template use File | Settings | File Templates.
 */
abstract class CCache extends CComponent implements ICache{
    abstract public function get($id);
    abstract public function set($id, $value, $expire = 0);
    abstract public function hasCache($key);
    abstract public function delete($key);
}

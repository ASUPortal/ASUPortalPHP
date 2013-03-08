<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */
class CCacheFile extends CCache {
    private $_config = null;
    public function __construct(array $config) {
        $this->_config = $config;
    }
    public function get($key) {
        if ($this->hasCache($key)) {
            $filename = md5($key);
            $content = file_get_contents($this->_config["cacheDir"].$filename.".cache");
            $obj = unserialize($content);
            return $obj;
        }
        return null;
    }
    public function set($key, $value) {
        $filename = md5($key);
        if ($this->hasCache($key)) {
            unlink($this->_config["cacheDir"].$filename.".cache");
        }

        $fHandler = fopen($this->_config["cacheDir"].$filename.".cache", "w");
        $str = serialize($value);
        fwrite($fHandler, $str);
        fclose($fHandler);
    }
    public function hasCache($key) {
        $filename = md5($key);

        if (file_exists($this->_config["cacheDir"].$filename.".cache")) {
            $now = time();
            $created = filemtime($this->_config["cacheDir"].$filename.".cache");

            if ($now - $created > $this->_config["timeout"]) {
                unlink($this->_config["cacheDir"].$filename.".cache");
                return false;
            }
            return true;
        }
        return false;
    }
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр Бармин
 * Date: 07.08.12
 * Time: 17:08
 * To change this template use File | Settings | File Templates.
 */
class CCacheFile extends CCache {
    protected $cacheDir = "";
    protected $timeout = 60;

    public function get($key) {
        if ($this->hasCache($key)) {
            $filename = md5($key);
            $content = file_get_contents($this->cacheDir.$filename.".cache");
            $obj = unserialize($content);
            return $obj;
        }
        return null;
    }
    public function hasCache($key) {
        $filename = md5($key);
        CUtils::createFoldersToPath($this->cacheDir);
        if (file_exists($this->cacheDir.$filename.".cache")) {
            $now = time();
            $created = filemtime($this->cacheDir.$filename.".cache");

            if ($now - $created > $this->timeout) {
                unlink($this->cacheDir.$filename.".cache");
                return false;
            }
            return true;
        }
        return false;
    }
    public function set($key, $value, $expires = 60) {
        $filename = md5($key);
        if ($this->hasCache($key)) {
            unlink($this->cacheDir.$filename.".cache");
        }

        $fHandler = fopen($this->cacheDir.$filename.".cache", "w");
        $str = serialize($value);
        fwrite($fHandler, $str);
        fclose($fHandler);
    }
}

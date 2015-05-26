<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 26.05.15
 * Time: 23:20
 */

class CCacheMemcache extends CCache{
    protected $timeout = 60;
    protected $serverHost = "localhost";
    protected $serverPort = "11211";

    private $_memcache;

    function __construct()
    {
        $this->_memcache = new Memcache();
        $this->_memcache->connect($this->serverHost, $this->serverPort) or die(
            "Не могу соединиться с сервером Memcached - сервер ".$this->serverHost.":".$this->serverPort
        );
    }


    public function get($id)
    {
        if ($this->hasCache($id)) {
            return $this->_memcache->get($id);
        }
    }

    public function set($id, $value, $expire = 0)
    {
        $this->_memcache->add($id, $value, false, $expire);
    }

    public function hasCache($key)
    {
        return ($this->_memcache->get($key) !== false);
    }

}
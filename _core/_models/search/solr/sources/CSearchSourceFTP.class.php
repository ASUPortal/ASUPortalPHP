<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:24
 */

class CSearchSourceFTP extends CComponent implements ISearchSource {
    public $server = "";
    public $login = "";
    public $password = "";
    public $id;
    public $path;
    public $link;
    public $suffix;

    protected function init() {
        $this->server = CSettingsManager::getSettingValue($this->server);
        $this->login = CSettingsManager::getSettingValue($this->login);
        $this->password = CSettingsManager::getSettingValue($this->password);
        //установка соединения с FTP-сервером
        $this->link = ftp_connect($this->server);
        //форматы файлов для индексирования
        $this->suffix = CSettingsManager::getSettingValue($this->suffix);
        //путь к папке с файлами для индексирования
        $this->path = CSettingsManager::getSettingValue($this->path);
        //массив с путями к файлам для индексирования
        $this->files = array();
    }
    
    private function scanDirectory() {
    	return $this->raw_list($this->path);
    }
    
    public function getFilesToIndex() {
    	//массив с файлами ftp сервера
    	$filelist = array();
    	$ftp_server = $this->server;
    	$ftp_user = $this->login;
    	$ftp_password = $this->password;
    	//пытаемся установить соединение
    	$link = $this->link;
    	if (!$link) {
    		exit;
    	} else {
    		//осуществляем регистрацию на сервере
    		$login = ftp_login($link, $ftp_user, $ftp_password);
    		if (!$login) {
    			exit;
    		} else {
    			//получаем все файлы указанного каталога
    			$filelist = $this->scanDirectory();
    			/*if (!empty($filelist)) {
    				foreach ($filelist as $server_file) {
    					$asciiArray = array("txt", "csv");
    					$extension = end(explode(".", $server_file));
    					if (in_array($extension, $asciiArray)) {
    						$mode = FTP_ASCII;
    					} else {
    						$mode = FTP_BINARY;
    					}
    					//информация о пути к файлу
    					$path_parts = pathinfo($server_file);
    					//название файла
    					$fileName = $path_parts["basename"];
    					//попытка скачать $server_file и сохранить в $local_file
    					$local_file = CORE_CWD.CORE_DS."tmp".CORE_DS."files_for_indexing".CORE_DS.$fileName;
    					if (ftp_get($link, $local_file, $server_file, $mode)) {
    						$ch = curl_init();
    						//$local_file - полный путь до файла
    						$data = array("myfile"=>"@".$local_file);
    						curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles(md5($server_file), urlencode($fileName), urlencode("ftp://".$server_file)));
    						curl_setopt($ch, CURLOPT_POST, 1);
    						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    						$result = curl_exec($ch);
    						if ($result === false) {
    							break;
    						}
    					} else {
    						break;
    					}
    					//удаляем локальный файл
    					unlink($local_file);
    				}
    			}*/
    		}
    		// закрываем соединение FTP
    		ftp_close($link);
    	}
    	$files = array();
    	$suffixes = explode(";", $this->suffix);
    	foreach ($filelist as $file) {
    		$extension = end(explode(".", $file));
    		if (in_array($extension, $suffixes)) {
    			$files[] = $file;
    		}
    	}
    	var_dump($files);
    	return new CSearchSourceFTPIterator($files, $this);
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFile(CSearchFile $fileDescriptor)
    {
        // TODO: Implement getFile() method.
    }
    
    public function raw_list($folder) {
    	$link = $this->link;
    	$suffix = $this->suffix;
    	$suffixes = explode(";", $suffix);
    	$list = ftp_rawlist($link, $folder);
    	$anzlist = count($list);
    	$i = 0;
    	while ($i < $anzlist) {
    		$split = preg_split("/[\s]+/", $list[$i], 9, PREG_SPLIT_NO_EMPTY);
    		$ItemName = $split[8];
    		$endung = strtolower(substr(strrchr($ItemName,"."),1));
    		$path = "$folder/$ItemName";
    		if (substr($list[$i],0,1) === "d" AND substr($ItemName,0,1) != ".") {
    			$this->raw_list($path);
    		} elseif (substr($ItemName,0,2) != "._" AND in_array($endung,$suffixes)) {
    			array_push($this->files, $path);
    		}
    		$i++;
    	}
    	return $this->files;
    }
    
}
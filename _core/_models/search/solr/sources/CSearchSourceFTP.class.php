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
        // установка соединения с FTP-сервером
        $this->link = ftp_connect($this->server);
        // форматы файлов для индексирования
        $this->suffix = CSettingsManager::getSettingValue($this->suffix);
        // путь к папке с файлами для индексирования
        $this->path = CSettingsManager::getSettingValue($this->path);
        // массив с путями к файлам для индексирования
        $this->files = array();
    }
    
    private function scanDirectory() {
    	return $this->ftpRecursiveFileListing($this->path);
    }
    
    /**
     * Получение файлов для индексирования
     *
     * @param CSearchSettings $coreId
     */
    public function getFilesToIndex(CSearchSettings $coreId) {
    	$filesList = array();
    	// массив с файлами ftp сервера
    	$ftpFiles = array();
    	$ftpServer = $this->server;
    	$ftpUser = $this->login;
    	$ftpPassword = $this->password;
    	// пытаемся установить соединение
    	$link = $this->link;
    	if (!$link) {
    		throw new Exception("<font color='#FF0000'>Не удается установить соединение с FTP-сервером: 
    				<a href='ftp://".$ftpServer."/' target='_blank'>ftp://".$ftpServer."/</a></font>");
    	}
    	// осуществляем регистрацию на сервере
    	$login = ftp_login($link, $ftpUser, $ftpPassword);
    	if (!$login) {
    		throw new Exception("<font color='#FF0000'>Не удается зарегистрироваться на FTP-сервере:
    					<a href='ftp://".$ftpServer."/' target='_blank'>ftp://".$ftpServer."/</a>. Проверьте регистрационные данные.</font>");
    	}
    	// получаем все файлы указанного каталога
    	$ftpFiles = $this->scanDirectory();
    	if (!empty($ftpFiles)) {
    		foreach ($ftpFiles as $serverFile) {
    			$asciiArray = array("txt", "csv");
    			$extension = end(explode(".", $serverFile));
    			if (in_array($extension, $asciiArray)) {
    				$mode = FTP_ASCII;
    			} else {
    				$mode = FTP_BINARY;
    			}
    			$fileName = CFileUtils::getFileName($serverFile);
    			// попытка скачать $serverFile и сохранить в $localFile
    			$localFile = CORE_CWD.CORE_DS."tmp".CORE_DS."files_for_indexing".CORE_DS.$fileName;
    			if (ftp_get($link, $localFile, $serverFile, $mode)) {
    				// сохраняем в названии файла путь до локальной папки и до папки ftp сервера
    				$filesList[] = $localFile."||".$serverFile;
    			} else {
    				break;
    			}
    		}
    	}
    	// закрываем соединение FTP
    	ftp_close($link);
    	$files = array();
    	$suffixes = explode(";", $this->suffix);
    	foreach ($filesList as $file) {
    		$extension = end(explode(".", $file));
    		if (in_array($extension, $suffixes)) {
    			$files[] = $file;
    		}
    	}
    	return new CSearchSourceFTPIterator($files, $this);
    }
    
    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function getFile(CSearchFile $fileDescriptor) {
        return $fileDescriptor;
    }
    
    function ftpRecursiveFileListing($path) {
    	$link = $this->link;
    	$suffix = $this->suffix;
    	$suffixes = explode(";", $suffix);
    	$contents = ftp_nlist($link, $path);
    	foreach($contents as $currentFile) {
    		if (strpos($currentFile, '.') === false) {
    			$this->ftpRecursiveFileListing($currentFile);
    		}
    		$extension = end(explode(".", $currentFile));
    		if (in_array($extension, $suffixes)) {
    			array_push($this->files, $currentFile);
    		}
    	}
    	return $this->files;
    }
}
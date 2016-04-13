<?php
//индексация из папки LocalHost
class CIndexLocalHost extends CAbstractIndexSolr {
	public function __construct() {
		//форматы файлов для индексирования
		$this->suffix = CSettingsManager::getSettingValue("formats_files_for_indexing");
		//путь к папке с файлами для индексирования
		$this->folder = CSettingsManager::getSettingValue("path_for_indexing_files");
		//список файлов в папке и подпапках
		$this->files = CUtils::getListFiles($this->folder);
	
		parent::__construct();
	}
    public function getListIndexingFiles() {
    	//выводимые сообщения о результатах обработки файлов
    	$messages = array();
    	$folder = $this->folder;
    	$all_files = $this->files;
    	//массив с файлами
    	$filelist = array();
    	$suffixes = explode(";", $this->suffix);
    	foreach ($all_files as $file) {
    		$extension = end(explode(".", $file));
    		if (in_array($extension, $suffixes)) {
    			$filelist[] = $file;
    		}
    	}
    	if (!empty($filelist)) {
    		foreach($filelist as $file) {
    			$messages[] = "START index from localhost";
    			$ch = curl_init();
    			$data = array("myfile"=>"@".$file); //$file - полный путь до файла
    			//информация о пути к файлу
    			$path_parts = pathinfo($file);
    			$fileName = $path_parts["basename"];
    			curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles(md5($file), $fileName, urlencode($file)));
    			curl_setopt($ch, CURLOPT_POST, 1);
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    			$result = curl_exec($ch);
    			if ($result === FALSE) {
    				$messages[] = "<font color='#FF0000'>cURL Error: ".curl_error($ch)."</font>";
    				break;
    			}
    			$error_no = curl_errno($ch);
    			curl_close($ch);
    			if ($error_no == 0) {
    				$messages[] = "<font color='#00CC00'>Файл ".$file." успешно загружен в индекс</font>";
    			} else {
    				$messages[] = "<font color='#FF0000'>Ошибка загрузки файла ".$file." в индекс</font>";
    			}
    			$messages[] = "END index from localhost";
    			$messages[] = "";
    		}
    	}
    	$countFiles = count($filelist);
    	$messages[] = "Обработано ".$countFiles. " файлов";
    	return $messages;
    }

    public function indexingFiles() {
    	$folder = $this->folder;
    	$all_files = $this->files;
    	$suffixes = explode(";", $this->suffix);
    	//массив с файлами
    	$filelist = array();
    	foreach ($all_files as $file) {
    		$extension = end(explode(".", $file));
    		if (in_array($extension, $suffixes)) {
    			$filelist[] = $file;
    		}
    	}
    	if (!empty($filelist)) {
    		foreach($filelist as $file) {
    			$ch = curl_init();
    			$data = array("myfile"=>"@".$file); //$file - полный путь до файла
    			//информация о пути к файлу
    			$path_parts = pathinfo($file);
    			$fileName = $path_parts["basename"];
    			curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles(md5($file), $fileName, urlencode($file)));
    			curl_setopt($ch, CURLOPT_POST, 1);
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    			$result = curl_exec($ch);
    			if ($result === FALSE) {
    				break;
    			}
    			curl_close($ch);
    		}
    	}
    }
    
}
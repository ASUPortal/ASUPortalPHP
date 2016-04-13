<?php
//индексация с FTP-сервера
class CIndexFTP extends CAbstractIndexSolr {
	public function __construct() {
		//путь к FTP-серверу
		$this->ftp_server = CSettingsManager::getSettingValue("ftp_server");
		//пользователь FTP-сервера
		$this->ftp_user = CSettingsManager::getSettingValue("ftp_server_user");
		//пароль FTP-сервера
		$this->ftp_password = CSettingsManager::getSettingValue("ftp_server_password");
		//установка соединения с FTP-сервером
		$this->link = ftp_connect($this->ftp_server);
		//массив с путями к файлам для индексирования
		$this->files = array();
		//форматы файлов для индексирования
		$this->suffix = CSettingsManager::getSettingValue("formats_files_for_indexing");
		//путь к папке с файлами для индексирования
		$this->startdir = CSettingsManager::getSettingValue("path_for_indexing_files_from_ftp");
		
		parent::__construct();
	}
    public function getListIndexingFiles() {
    	//выводимые сообщения о результатах обработки файлов
    	$messages = array();
    	//массив с файлами
    	$filelist = array();
    	$ftp_server = $this->ftp_server;
    	$ftp_user = $this->ftp_user;
    	$ftp_password = $this->ftp_password;
    	//пытаемся установить соединение
    	$link = $this->link;
    	if (!$link) {
    		$messages[] = "<font color='#FF0000'>К сожалению, не удается установить соединение с FTP-сервером: <a href='ftp://".$ftp_server."/' target='_blank'>ftp://".$ftp_server."/</a></font>";
    		$messages[] = "";
    	} else {
    		//осуществляем регистрацию на сервере
    		$login = ftp_login($link, $ftp_user, $ftp_password);
    		if (!$login) {
    			$messages[] = "<font color='#FF0000'>К сожалению, не удается зарегистрироваться на FTP-сервере. Проверьте регистрационные данные</font>";
    			$messages[] = "";
    		} else {
    			$startdir = $this->startdir;
    			//получаем все файлы указанного каталога
    			$filelist = $this->raw_list($startdir);
    			if (!empty($filelist)) {
    				foreach ($filelist as $server_file) {
    					$messages[] = "START index from FTP";
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
    						$messages[] = "Произведена запись в локальный файл ".$local_file;
    						$ch = curl_init();
    						$data = array("myfile"=>"@".$local_file); //$local_file - полный путь до файла
    						curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles(md5($server_file), $fileName,
    								urlencode("ftp://".$ftp_user.":".$ftp_password."@".$ftp_server."/".$server_file)));
    						curl_setopt($ch, CURLOPT_POST, 1);
    						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    						$result = curl_exec($ch);
    						if ($result === false) {
    							$messages[] = "<font color='#FF0000'>cURL Error: ".curl_error($ch)."</font>";
    							break;
    						}
    						$error_no = curl_errno($ch);
    						curl_close($ch);
    						if ($error_no == 0) {
    							$messages[] = "<font color='#00CC00'>Файл ftp://".$ftp_server."/".$server_file." успешно загружен в индекс</font>";
    						} else {
    							$messages[] = "<font color='#FF0000'>Ошибка загрузки файла ".$local_file." в индекс</font>";
    						}
    					} else {
    						$messages[] = "Не удалось произвести запись в локальный файл";
    					}
    					$messages[] = "END index from FTP";
    					$messages[] = "";
    					//удаляем локальный файл
    					unlink($local_file);
    				}
    			}
    		}
    		// закрываем соединение FTP
    		ftp_close($link);
    	}
    	$countFiles = count($filelist);
    	$messages[] = "Обработано ".$countFiles. " файлов";
    	return $messages;
    }

    public function indexingFiles() {
    	//массив с файлами
    	$filelist = array();
    	$ftp_server = $this->ftp_server;
    	$ftp_user = $this->ftp_user;
    	$ftp_password = $this->ftp_password;
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
    			$startdir = $this->startdir;
    			//получаем все файлы указанного каталога
    			$filelist = $this->raw_list($startdir);
    			if (!empty($filelist)) {
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
    						curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles(md5($server_file), $fileName,
    								urlencode("ftp://".$ftp_user.":".$ftp_password."@".$ftp_server."/".$server_file)));
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
    			}
    		}
    		// закрываем соединение FTP
    		ftp_close($link);
    	}
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
<?php
//индексация с FTP-сервера
class CIndexFTP extends CAbstractIndexSolr {
    public function getListIndexingFiles() {
    	//выводимые сообщения о результатах обработки файлов
    	$messages = array();
    	//массив с файлами
    	$filelist = array();
    	$ftp_server = CSettingsManager::getSettingValue("ftp_server");
    	$ftp_user = CSettingsManager::getSettingValue("ftp_server_user");
    	$ftp_password = CSettingsManager::getSettingValue("ftp_server_password");
    	//пытаемся установить соединение
    	$link = ftp_connect($ftp_server);
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
    			//получаем все файлы корневого каталога
    			$filelist = ftp_nlist($link, "/");
    			//получаем все файлы указанного каталога
    			$filelist = ftp_nlist($link, CSettingsManager::getSettingValue("path_for_indexing_files_from_ftp"));
    			if (!empty($filelist)) {
    				$arr = explode(";", CSettingsManager::getSettingValue("formats_files_for_indexing"));
    				foreach ($filesFtp as $server_file) {
    					foreach ($arr as $key=>$value) {
    						if (strpos($server_file, $value) !== false) {
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
    							$fileName = $path_parts["basename"];
    							//попытка скачать $server_file и сохранить в $local_file
    							$local_file = CORE_CWD.CORE_DS."tmp".CORE_DS."files_for_indexing".CORE_DS.$fileName;
    							if (ftp_get($link, $local_file, $server_file, $mode)) {
    								$messages[] = "Произведена запись в локальный файл ".$local_file;
    								$ch = curl_init();
    								//$local_file - полный путь до файла
    								$data = array("myfile"=>"@".$local_file); 
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
    	$ftp_server = CSettingsManager::getSettingValue("ftp_server");
    	$ftp_user = CSettingsManager::getSettingValue("ftp_server_user");
    	$ftp_password = CSettingsManager::getSettingValue("ftp_server_password");
    	//пытаемся установить соединение
    	$link = ftp_connect($ftp_server);
    	if (!$link) {
    		exit;
    	} else {
    		//осуществляем регистрацию на сервере
    		$login = ftp_login($link, $ftp_user, $ftp_password);
    		if (!$login) {
    			exit;
    		} else {
    			//получаем все файлы корневого каталога
    			$filelist = ftp_nlist($link, "/");
    			//получаем все файлы указанного каталога
    			$filelist = ftp_nlist($link, CSettingsManager::getSettingValue("path_for_indexing_files_from_ftp"));
    			if (!empty($filelist)) {
    				$arr = explode(";", CSettingsManager::getSettingValue("formats_files_for_indexing"));
    				foreach ($filesFtp as $server_file) {
    					foreach ($arr as $key=>$value) {
    						if (strpos($server_file, $value) !== false) {
    							$asciiArray = array("txt", "csv");
    							$extension = end(explode(".", $server_file));
    							if (in_array($extension, $asciiArray)) {
    								$mode = FTP_ASCII;
    							} else {
    								$mode = FTP_BINARY;
    							}
    							//информация о пути к файлу
    							$path_parts = pathinfo($server_file);
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
    			}
    		}
    		// закрываем соединение FTP
    		ftp_close($link);
    	}
    }
    
}
<?php
//индексация из папки LocalHost
class CIndexLocalHost extends CAbstractIndexSolr {
    public function getListIndexingFiles() {
    	//выводимые сообщения о результатах обработки файлов
    	$messages = array();
    	$folder = CSettingsManager::getSettingValue("path_for_indexing_files");
    	$all_files = CUtils::getListFiles($folder);
    	//массив с файлами
    	$filelist = array();
    	$arr = explode(";", CSettingsManager::getSettingValue("formats_files_for_indexing"));
    	foreach ($all_files as $file) {
    		foreach ($arr as $key=>$value) {
    			if (strpos($file, $value) !== false) {
    				$filelist[] = $file;
    			}
    		}
    	}
    	if (!empty($filelist)) {
    		foreach($filelist as $file) {
    			$messages[] = "START index from localhost";
    			$ch = curl_init();
    			//$file - полный путь до файла
    			$data = array("myfile"=>"@".$file);
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
    	$folder = CSettingsManager::getSettingValue("path_for_indexing_files");
    	$all_files = CUtils::getListFiles($folder);
    	//массив с файлами
    	$filelist = array();
    	$arr = explode(";", CSettingsManager::getSettingValue("formats_files_for_indexing"));
    	foreach ($all_files as $file) {
    		foreach ($arr as $key=>$value) {
    			if (strpos($file, $value) !== false) {
    				$filelist[] = $file;
    			}
    		}
    	}
    	if (!empty($filelist)) {
    		foreach($filelist as $file) {
    			$ch = curl_init();
    			//$file - полный путь до файла
    			$data = array("myfile"=>"@".$file);
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
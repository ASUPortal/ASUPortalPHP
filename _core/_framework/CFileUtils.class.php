<?php

class CFileUtils {
	/**
	 * Размер файла по указанному пути в мегабайтах
	 *
	 * @param $fileName
	 * @return string
	 */
	public static function getFileSize($fileName) {
		if ($fileName!='') {
			if (file_exists(CORE_CWD.CORE_DS.$fileName)) {
				return round(filesize(CORE_CWD.CORE_DS.$fileName)/1024/1024,3);
			} else {
				return 0;
			}
		} else {
			throw new Exception("Попытка получить размер для не указанного файла!");
		}
	}
	
    /**
     * Список файлов в папке и подпапках
     *
     * @param string $folder
     * @return array
     */
    public static function getListFiles($folder) {
        $files = array();
        
        if (file_exists($folder)) {
        	$handler = opendir($folder);
        	while ($file = readdir($handler)) {
        		$filename = $folder.CORE_DS.$file;
        		if (is_file($filename)) {
        			$files[] = $filename;
        		} elseif ($file != "." && $file != "..") {
        			$filename .= CORE_DS;
        			$childFiles = CFileUtils::getListFiles($filename);
        			foreach ($childFiles as $childFile) {
        				$files[] = $childFile;
        			}
        		}
        	}
        	closedir($handler);
        }
        
        return $files;
    }
    
    /**
     * Название файла по указанному пути
     *
     * @param $fileName
     * @return string
     */
    public static function getFileName($folder) {
    	if ($folder!='') {
    		$pathParts = pathinfo($folder);
    		$fileName = $pathParts["basename"];
    		return $fileName;
    	} else {
			throw new Exception("Попытка получить название для не указанного файла!");
		}
    }
}

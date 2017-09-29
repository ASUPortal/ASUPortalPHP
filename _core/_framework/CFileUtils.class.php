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
    
    /**
     * Получить ссылку на файл
     * 
     * @param string $name
     * @param CModel $model
     * @return string|NULL
     */
    public static function getLinkAttachment($name, CModel $model) {
        $attributes = $model->fieldsProperty();
        $field = $attributes[$name];
        $storage = $field["upload_dir"];
        $file = $model->$name;
        if (is_file($storage.$file)) {
            $linkWithBackSlash = $storage.$file;
            $link = str_replace('\\', '/', $linkWithBackSlash);
            return $link;
        } else {
            return null;
        }
    }
    
    /**
     * Создать архив из массива с файлами
     * 
     * @param CArrayList $filesWithNames
     * @param string $archiveName
     * @return string
     */
    public static function createZipArchiveFromArray($filesWithNames, $archiveName) {
        $zip = new ZipArchive();
        $archiveName = $archiveName."_".date("dmY_Hns").".zip";
        if (CSettingsManager::getSettingValue("template_filename_translit")) {
        	$archiveName = CUtils::toTranslit($archiveName);
        }
        $zip->open(ZIP_DOCUMENTS_DIR.$archiveName, ZipArchive::CREATE);
    	foreach ($filesWithNames as $path=>$name) {
            $extension = end(explode(".", $path));
            if (CSettingsManager::getSettingValue("template_filename_translit")) {
                $name = CUtils::toTranslit($name);
            }
            $fileName = $name.".".$extension;
            $zip->addFile($path, $fileName);
        }
        $zip->close();
        header("location: ".ZIP_DOCUMENTS_URL.$archiveName);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.04.16
 * Time: 20:21
 */

class CSolrManager extends CComponent {
    public $sources = array();

    protected function init() {
        foreach ($this->sources as $index=>$source) {
            $class = $source["class"];
            unset($source["class"]);
            $sourceObject = new $class($source);
            $this->sources[$index] = $sourceObject;
        }
    }

    public function updateIndex() {
    	$messages = array();
        foreach ($this->sources as $source) {
            try {
                foreach ($source->getFilesToIndex() as $file) {
                    $messages[] = $this->addToIndex($file);
                }
            } catch (Exception $e) {
                // тут будет исключение
                echo "<font color='#FF0000'>".$e->getMessage()."</font><br>";
            }
        }
        return $messages;
    }

    private function addToIndex(CSearchFile $file) {
        CApp::getApp()->cache->set($file->getFileId(), $file);
        // сообщение о результате обработки файла
        $message = "";
        // добавление в Solr
        $ch = curl_init();
        $data = array("myfile"=>"@".$file->getFileSource());
        curl_setopt($ch, CURLOPT_URL, CSolr::commitFiles($file->getFileId()));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
        	$message .= "<font color='#FF0000'>cURL Error: ".curl_error($ch)."</font>";
        	throw new Exception("Не удалось добавить файл в индекс Solr!");
        	break;
        }
        $error_no = curl_errno($ch);
        curl_close($ch);
        if ($error_no == 0) {
        	$message .= "<font color='#00CC00'>Файл ".$file->getFileLocation()." успешно загружен в индекс</font>";
        } else {
        	$message .= "<font color='#FF0000'>Ошибка загрузки файла ".$file->getFileLocation()." в индекс</font>";
        }

        // удаляем локальный файл из временной папки для ftp
        if (CUtils::strLeft($file->getFileId(), "||") == "ftp_portal") {
        	unlink($file->getFileSource());
        }
        
        return $message;

        /*
         * $solrObject.id = $file->getId()
         * $solrObject.$fileSource
         * $solrObject.$realFilePath
         * $solrObject.$sourceId
         */
    }

    public function getFile($fileId) {
        $sourceId = CUtils::strLeft($fileId, "||");
        foreach ($this->sources as $source) {
            if ($source->getId() == $sourceId) {
                // попросить у солра документ по id
                $searchFile = CApp::getApp()->cache->get($fileId);
                if (!empty($searchFile)) {
                	return $source->getFile($searchFile);
                } else {
                	throw new Exception("Кэш был очищен, обновите файловый индекс!");
                }
            }
        }
        return null;
    }
}
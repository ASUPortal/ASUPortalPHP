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
        foreach ($this->sources as $source) {
            try {
                foreach ($source->getFilesToIndex() as $file) {
                    $this->addToIndex($file);
                }
            } catch (Exception $e) {
                // тут будет исключение
                var_dump($e);
            }
        }
    }

    private function addToIndex(CSearchFile $file) {
        // добавление в солр
        var_dump($file->getFileId());

        CApp::getApp()->cache->set("tempFile", $file);

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
                // $searchFile = new CSearchFile();
                $searchFile = CApp::getApp()->cache->get("tempFile");
                return $source->getFile($searchFile);
            }
        }
        return null;
    }
}
<?php

abstract class CAbstractIndexSolr extends CComponent implements ISolrInterface {

    public function getListIndexingFiles()
    {
        return array();
    }

    public function indexingFiles()
    {
        return array();
    }

}
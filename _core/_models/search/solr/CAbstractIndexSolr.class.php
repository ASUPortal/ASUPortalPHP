<?php

abstract class CAbstractIndexSolr extends CComponent {

    public function getListIndexingFiles()
    {
        return array();
    }

    public function indexingFiles()
    {
        return array();
    }

}
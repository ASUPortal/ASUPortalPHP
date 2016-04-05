<?php
	//добавление файлов в индекс Solr
    require_once("core.php");
    CApp::getApp()->solr->indexingFiles();
?>
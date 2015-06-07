<?php

class CSearchCatalogQuestionStatus extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        $query = new CQuery();
        $query->select("distinct(post.id) as id, stat.name as name")
	        ->from(TABLE_QUESTION_STATUS." as stat")
	        ->condition("stat.name like '%".$lookup."%'")
        	->limit(0, 5);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        $stat = CQuestionManager::getQuestionStatus($id);
        if (!is_null($stat)) {
            $result[$stat->getId()] = $stat->getValue();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        $query = new CQuery();
        $query->select("distinct(stat.id) as id, stat.name as name")
	        ->from(TABLE_QUESTION_STATUS." as stat");
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }
    public function actionGetCreationActionUrl()
    {
        // TODO: Implement actionGetCreationActionUrl() method.
    }

    public function actionGetObject($id)
    {
        return CQuestionManager::getQuestionStatus($id);
    }

}
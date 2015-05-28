<?php

class CSearchCatalogPostRate extends CAbstractSearchCatalog{
    public function actionTypeAhead($lookup)
    {
        $result = array();
        // показываем только должности, по которым нет ставок
        $query = new CQuery();
        $query->select("distinct(post.id) as id, post.name as name")
	        ->from(TABLE_POSTS." as post")
	        ->condition("post.name like '%".$lookup."%' and id not in (select `dolgnost_id` from `hours_rate`)")
        	->limit(0, 10);
        foreach ($query->execute()->getItems() as $item) {
            $result[$item["id"]] = $item["name"];
        }
        return $result;
    }

    public function actionGetItem($id)
    {
        $result = array();
        // выбор должностей
        $post = CTaxonomyManager::getPostById($id);
        if (!is_null($post)) {
            $result[$post->getId()] = $post->getValue();
        }
        return $result;
    }

    public function actionGetViewData()
    {
        $result = array();
        // показываем только должности, по которым нет ставок
        $query = new CQuery();
        $query->select("distinct(post.id) as id, post.name as name")
	        ->from(TABLE_POSTS." as post")
	        ->condition("id not in (select `dolgnost_id` from `hours_rate`)");
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
        return CTaxonomyManager::getPostById($id);
    }

}
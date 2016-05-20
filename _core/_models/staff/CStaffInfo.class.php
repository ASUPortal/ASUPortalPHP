<?php

class CStaffInfo {
    /**
     * Отображение информации о сотруднике с помощью html
     *
     * @param $name
     * @param CModel $model
     * @param int $size
     */
    public static function infoStaff(CModel $model, $size = 200) {
    	$pageContent = "";
    	$attributes = $model->fieldsProperty();
    	$display = false;
    	if (array_key_exists("photo", $attributes)) {
    		$field = $attributes["photo"];
    		if ($field["type"] == FIELD_UPLOADABLE) {
    			$storage = $field["upload_dir"];
    			$file = $model->photo;
    			if ($file !== "") {
    				if (file_exists($storage.$file)) {
    					$display = true;
    				}
    			}
    		}
    	}
    	if ($model->work_place != "") {
    		$pageContent .= "<b>".$model->getName()."</b><br><br>";
    		if ($display) {
    			$pageContent .= '<a href="../../images/lects/'.$model->photo.'" target="_blank" class="image_clearboxy cboxElement">
    									<img src="../../_modules/_thumbnails/?src=/images/lects/'.$model->photo.'&amp;w='.$size.'"></a><br>';
    		}
    		if ($model->getPost() != "") {
    			$pageContent .= "Должность на кафедре: ".$model->getPost()."<br>";
    		}
    		$pageContent .= "Основное место работы: ".$model->work_place."<br>";
    		if ($model->getManuals()->getCount() != 0) {
    			$pageContent .= "Дисциплины:<br>";
    			foreach ($model->getManuals()->getItems() as $manual) {
    				$pageContent .= '<li><a href="../../_modules/_library/index.php?action=publicView&id='.$manual->nameFolder.'">'.$manual->name.' ('.$manual->f_cnt.')</a></li>';
    			}
    		}
    		$pageContent .= "<hr>";
    	}
    	return $pageContent;
    }
}
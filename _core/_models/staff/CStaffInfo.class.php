<?php

class CStaffInfo {
    /**
     * Получение информации о сотруднике-совместителе в виде html
     *
     * @param $name
     * @param CModel $model
     * @param int $size
     */
    public static function infoStaff(CPerson $person, $size = 200) {
    	$pageContent = "";
    	$attributes = $person->fieldsProperty();
    	$display = false;
    	if (array_key_exists("photo", $attributes)) {
    		$field = $attributes["photo"];
    		if ($field["type"] == FIELD_UPLOADABLE) {
    			$storage = $field["upload_dir"];
    			$file = $person->photo;
    			if ($file !== "") {
    				if (file_exists($storage.$file)) {
    					$display = true;
    				}
    			}
    		}
    	}
    	if ($person->work_place != "" and $person->is_slave == 1) {
    		$pageContent .= "<b>".$person->getName()."</b><br><br>";
    		if ($display) {
    			$pageContent .= '<a href="../../images/lects/'.$person->photo.'" target="_blank" class="image_clearboxy cboxElement">
    									<img src="../../_modules/_thumbnails/?src=/images/lects/'.$person->photo.'&amp;w='.$size.'"></a><br>';
    		}
    		if ($person->getPost() != "") {
    			$pageContent .= "Должность на кафедре: ".$person->getPost()."<br>";
    		}
    		$pageContent .= "Основное место работы: ".$person->work_place."<br>";
    		if ($person->getManuals()->getCount() != 0) {
    			$pageContent .= "Дисциплины:<br>";
    			foreach ($person->getManuals()->getItems() as $manual) {
    				$pageContent .= '<li><a href="../../_modules/_library/index.php?action=publicView&id='.$manual->nameFolder.'">'.$manual->name.' ('.$manual->f_cnt.')</a></li>';
    			}
    		}
    		$pageContent .= "<hr>";
    	}
    	return $pageContent;
    }
}
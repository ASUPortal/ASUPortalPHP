<?php

class CWorkPlanContentLoadModelOptionalValidator extends IModelValidatorOptional{
    public function getError() {
        return "Не совпадают значения нагрузки";
    }

    function onRead(CModel $model) {
    	$result = true;
    	foreach ($model->loads->getItems() as $load) {
    		$sum = 0;
    		foreach ($load->topics as $topic) {
    			$sum += $topic->value;
    		}
    		foreach ($load->technologies as $technology) {
    			$sum += $technology->value;
    		}
    		foreach ($load->selfEducations as $selfEdu) {
    			$sum += $selfEdu->question_hours;
    		}
    		if ($load->value != $sum) {
    			$result = false;
    		} else {
    			$result = true;
    		}
    	}
    	return $result;
    	
    	foreach ($model->loads->getItems() as $load) {
    		$sum = 0;
    		foreach ($load->topics as $topic) {
    			$sum += $topic->value;
    		}
    		foreach ($load->technologies as $technology) {
    			$sum += $technology->value;
    		}
    		foreach ($load->selfEducations as $selfEdu) {
    			$sum += $selfEdu->question_hours;
    		}
    		echo " ".$load->loadType->getValue()." ";
    		echo " ".$load->value." ";
    		echo $sum;
    		if ($load->value != $sum) {
    			echo " не верно";
    		} else {
    			echo " верно";
    		}
    	}
    	
    }

}
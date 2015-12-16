<?php

class CWorkPlanContentLoadModelOptionalValidator extends IModelValidatorOptional {
	private $model;
	
	function getError() {
		return implode($this->validate(), "; <br>");
	}
	
	function onRead(CModel $model) {
		$this->model = $model;
		if (count($this->validate()) > 0) {
			return false;
		} else {
			return true;
		}
	}
	
	function validate() {
		// тут валидация, она возвращает массив ошибок
		$errors = array();
		foreach ($this->model->loads->getItems() as $load) {
			$sum = 0;
			$topicValue = 0;
			foreach ($load->topics as $topic) {
				$topicValue += $topic->value;
				$sum += $topic->value;
			}
			$technologyValue = 0;
			foreach ($load->technologies as $technology) {
				$technologyValue += $technology->value;
				$sum += $technology->value;
			}
			$selfEduValue = 0;
			foreach ($load->selfEducations as $selfEdu) {
				$selfEduValue += $selfEdu->question_hours;
				$sum += $selfEdu->question_hours;
			}
			if ($load->value != $sum) {
				$errors[] = "Число часов нагрузки ".$load->loadType->getValue().": ".$load->value." не совпадает с суммой ".
						$sum.": темы (".$topicValue."), технологии (".$technologyValue."), вопросы (".$selfEduValue.")";
			}
			
		}
		return $errors;
	}
	
}
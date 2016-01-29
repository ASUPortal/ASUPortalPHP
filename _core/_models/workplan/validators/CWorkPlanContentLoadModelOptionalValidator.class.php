<?php

class CWorkPlanContentLoadModelOptionalValidator extends IModelValidatorOptional {
	private $model;
	
	function getError() {
		return implode($this->validate(), "; <br>");
	}
	
	function onRead(CModel $model) {
		$this->model = $model;
		return count($this->validate()) == 0;
	}
	
	function validate() {
		// тут валидация, она возвращает массив ошибок
		$errors = array();
		foreach ($this->model->loads->getItems() as $load) {
			// общая сумма по темам, технологиям и вопросам самостоятельного изучения
			$sum = 0;
			// сумма по темам
			$topicValue = 0;
			foreach ($load->topicsDisplay as $topic) {
				$topicValue += $topic->value;
				$sum += $topic->value;
			}
			// сумма по образовательным технологиям
			$technologyValue = 0;
			foreach ($load->technologiesDisplay as $technology) {
				$technologyValue += $technology->value;
				$sum += $technology->value;
			}
			// сумма по вопросам самостоятельного изучения
			$selfEduValue = 0;
			foreach ($load->selfEducationsDisplay as $selfEdu) {
				$selfEduValue += $selfEdu->question_hours;
				$sum += $selfEdu->question_hours;
			}
			if ($load->value != $topicValue) {
				$errors[] = "Число часов нагрузки ".$load->loadType->getValue().": ".$load->value." не совпадает с суммой часов по темам: ".
						$topicValue;
			}
			if ($technologyValue > $load->value) {
				$errors[] = "Число часов по образовательным технологиям: ".$technologyValue." превышает сумму часов по виду нагрузки ".
						$load->loadType->getValue().": ".$load->value;
			}
		}
		return $errors;
	}
	
}
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
		// общая сумма по темам, технологиям и вопросам самостоятельного изучения
		$sum = 0;
		foreach ($this->model->loadsDisplay->getItems() as $load) {
			// сумма по темам
			$topicValue = 0;
			foreach ($load->topicsDisplay as $topic) {
				if ($topic->load->getId() == $load->getId()) {
					$topicValue += $topic->value;
					$sum += $topic->value;
				}
			}
			if ($load->value != $topicValue) {
				$errors[] = "Число часов нагрузки ".$load->loadType->getValue().": ".$load->value." не совпадает с суммой часов по темам: ".
						$topicValue;
			}
			
			// сумма по образовательным технологиям
			$technologyValue = 0;
			foreach ($load->technologiesDisplay as $technology) {
				if ($technology->load->getId() == $load->getId()) {
					$technologyValue += $technology->value;
					$sum += $technology->value;
				}
			}
			if ($technologyValue > $load->value) {
				$errors[] = "Число часов по образовательным технологиям: ".$technologyValue." превышает сумму часов по виду нагрузки ".
						$load->loadType->getValue().": ".$load->value;
			}
			
			// сумма по вопросам самостоятельного изучения
			$selfEduValue = 0;
			foreach ($load->selfEducationsDisplay as $selfEdu) {
				if ($selfEdu->load->getId() == $load->getId()) {
					$selfEduValue += $selfEdu->question_hours;
					$sum += $selfEdu->question_hours;
				}
			}
			/*if ($load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
				if ($load->value != $selfEduValue) {
					$errors[] = "Число часов по СРС: ".$load->value." не совпадает с суммой часов по вопросам самостоятельного изучения: ".
							$selfEduValue;
				}
			}*/
		}
		return $errors;
	}
	
}
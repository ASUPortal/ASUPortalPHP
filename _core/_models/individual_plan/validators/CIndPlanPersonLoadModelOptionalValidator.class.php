<?php

class CIndPlanPersonLoadModelOptionalValidator extends IModelValidatorOptional {
	private $model;
	
	function getError() {
		return implode($this->validate(), "; <br>");
	}
	
	function onRead(CModel $model) {
		$this->model = $model;
		return count($this->validate()) == 0;
	}
	
	function validate() {
		$errors = array();
		if ($this->model->isEditRestriction()) {
			$errors[] = "Установлено ограничение на редактирование!";
		}
		return $errors;
	}
	
}
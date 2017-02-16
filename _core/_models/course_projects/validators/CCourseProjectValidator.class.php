<?php

class CCourseProjectValidator extends IModelValidatorOptional {
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
		$corriculum = $this->model->group->corriculum;
		$group = $this->model->group;
		if (is_null($corriculum)) {
			$errors[] = 'В группе <a href="'.WEB_ROOT.'_modules/_student_groups/index.php?action=edit&id='.$group->getId().'" target="_blank">'.
					$group->getName().'</a> не указан учебный план!';
		} else {
			foreach ($corriculum->cycles as $cycle) {
				foreach ($cycle->allDisciplines as $discipline) {
					if ($this->model->discipline->getId() == $discipline->discipline->getId()) {
						$corriculumDiscipline = '<a href="'.WEB_ROOT.'_modules/_corriculum/disciplines.php?action=edit&id='.$discipline->getId().'" target="_blank">'.$discipline->discipline->getValue().'</a>';
						foreach ($discipline->sections->getItems() as $section) {
							foreach ($section->labors->getItems() as $labor) {
								if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_WORK) {
									$courseProject = "указана курсовая работа";
								}
								if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_PROJECT) {
									$courseProject = "указан курсовой проект";
								}
							}
							
						}
						foreach ($discipline->plans->getItems() as $plan) {
							foreach ($plan->projectThemes->getItems() as $projectTheme) {
								$projectThemes[$projectTheme->getId()] = $projectTheme->project_title;
							}
						}
					}
				}
			}
			$errors[] = 'В учебном плане <a href="'.WEB_ROOT.'_modules/_corriculum/index.php?action=edit&id='.$corriculum->getId().'" target="_blank">'.$corriculum->title.'</a>,
					привязанном к группе <a href="'.WEB_ROOT.'_modules/_student_groups/index.php?action=edit&id='.$group->getId().'" target="_blank">'.$group->getName().'</a>,
					в нагрузке дисциплины '.$corriculumDiscipline.' '.$courseProject;
		}
		return $errors;
	}
	
}
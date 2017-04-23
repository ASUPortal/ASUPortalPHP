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
		
		$project = $this->model;
		$date = date("Y-m-d 00:00:00", strtotime($project->issue_date));
		$years = array();
		foreach (CActiveRecordProvider::getWithCondition(TABLE_YEARS, 'date_start <= "'.$date.'" and date_end >= "'.$date.'"')->getItems() as $ar) {
			$term = new CTerm($ar);
			$years[] = $term->getId();
		}
		$groupsByYear = array();
		if (!empty($years)) {
			$year = CTaxonomyManager::getYear($years[0]);
			foreach (CStaffManager::getStudentGroupsByYear($year)->getItems() as $groupByYear) {
				if ($groupByYear->getStudentsWithChangeGroupsHistory()->getCount() > 0) {
					$groupsByYear[] = $groupByYear->getId();
				}
			}
		}
		
		$groupMismatch = false;
		$students = new CArrayList();
		foreach ($project->tasks->getItems() as $task) {
			$student = CStaffManager::getStudent($task->student_id);
			if ($student->group_id != $group->getId()) {
				$groupMismatch = true;
			}
		}
		if (!in_array($group->getId(), $groupsByYear)) {
			$errors[] = 'Группа <a href="'.WEB_ROOT.'_modules/_student_groups/index.php?action=edit&id='.$group->getId().'" target="_blank">'.
					$group->getName().'</a> отсутствует в списке групп за указанный год! Обновите значение учебной группы и список заданий!';
		} elseif ($groupMismatch) {
			$errors[] = "Студенты, сохранённые в списке заданий, не соответствуют выбранной группе! Обновите список заданий!";
		} elseif (is_null($corriculum) and !$groupMismatch) {
			$errors[] = 'В группе <a href="'.WEB_ROOT.'_modules/_student_groups/index.php?action=edit&id='.$group->getId().'" target="_blank">'.
					$group->getName().'</a> не указан учебный план!';
		} else {
			$corriculumDiscipline = ", отсутствует дисциплина ".$this->model->discipline->getValue()."!";
			$courseProject = "не указана курсовая работа/проект!";
			foreach ($corriculum->cycles as $cycle) {
				foreach ($cycle->allDisciplines as $discipline) {
					if ($this->model->discipline->getId() == $discipline->discipline->getId()) {
						$corriculumDiscipline = ', в нагрузке дисциплины <a href="'.WEB_ROOT.'_modules/_corriculum/disciplines.php?action=edit&id='.$discipline->getId().'" target="_blank">'.$discipline->discipline->getValue().'</a>';
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
					привязанном к группе <a href="'.WEB_ROOT.'_modules/_student_groups/index.php?action=edit&id='.$group->getId().'" target="_blank">'.$group->getName().'</a>'.$corriculumDiscipline.' '.$courseProject;
		}
		return $errors;
	}
	
}
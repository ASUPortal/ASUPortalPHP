<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 11.12.15
 * Time: 23:23
 */

class CWorkPlanApproverModelOptionalValidator extends IModelValidatorOptional {
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
		$discipline = $this->model->corriculumDiscipline;
		if (!is_null($discipline->sections)) {
			$terms = array();
			$terms[] = "term.name";
			$termIds = array();
			$errorTerm = false;
			$sectionsDisciplines = array();
			if (!empty($discipline->sections->getItems())) {
				foreach ($discipline->sections->getItems() as $section) {
					$sectionsDisciplines[] = $section->id;
				}
			}
			foreach ($this->model->terms->getItems() as $term) {
				if (!in_array($term->number, $sectionsDisciplines)) {
					$errorTerm = true;
				}
				if (is_null($term->corriculum_discipline_section)) {
					$errorTerm = true;
				}
				$termIds[] = $term->getId();
				$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
			}
			if ($errorTerm) {
				$errors[] = "<b><font color='#FF0000'>Обновите названия семестров из дисциплины!</font></b>";
			}
			if (count($termIds) > 0) {
				$terms[] = "sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum";
			}
			$workPlanTotalHours = new CWorkPlanTotalHours();
			$totalHours = $workPlanTotalHours->execute($this->model);
			$totalCredits = round($totalHours/36, 2);
			if(intval($totalCredits) != $totalCredits) {
				$errors[] = "<b>Число зачётных единиц дисциплины (".$totalCredits.") должно быть целым (cумма часов: ".$totalHours.")</b>";
			}
			foreach ($this->model->terms as $term) {
				foreach ($discipline->sections->getItems() as $sect) {
					if ($term->number == $sect->id) {
						$sumAuditor = 0;
						$sumHours = 0;
						$sumExamUnit = 0;
						$sumKSR = 0;
						$sumCourseWork = 0;
						$sumCourseProject = 0;
						$sumLabWork = 0;
						$sumLecture = 0;
						$sumTotal = 0;
						$sumPractice = 0;
						$sumSelfWork = 0;
						$sumRGR = 0;
						$sumExamen = CTaxonomyManager::getTaxonomy("corriculum_final_control_hours")->getTerm("examenHours")->getValue();
						$sumCredit = CTaxonomyManager::getTaxonomy("corriculum_final_control_hours")->getTerm("creditHours")->getValue();
						$sumCreditWithMark = CTaxonomyManager::getTaxonomy("corriculum_final_control_hours")->getTerm("creditHours")->getValue();
						foreach ($this->model->categories as $category) {
							foreach ($category->sections as $section) {
								foreach ($section->loadsDisplay as $load) {
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_AUDITOR) {
										$sumAuditor += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL_THEORETIC_EDUCATION) {
										$sumHours += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_EXAM_UNIT) {
										$sumExamUnit += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR) {
										$sumKSR += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_WORK) {
										$sumCourseWork += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_PROJECT) {
										$sumCourseProject += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK) {
										$sumLabWork += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE) {
										$sumLecture += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL) {
										$sumTotal += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE) {
										$sumPractice += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
										$sumSelfWork += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_RGR) {
										$sumRGR += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_EXAMEN) {
										$sumExamen += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_CREDIT) {
										$sumCredit += $load->value;
									}
									if ($load->term->number == $term->number and $load->loadType->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_CREDIT_WITH_MARK) {
										$sumCreditWithMark += $load->value;
									}
								}
							}
						}
						$auditorZan = $sumLecture+$sumPractice+$sumLabWork;
						$teorObuch = $auditorZan+$sumSelfWork;
						foreach ($sect->labors->getItems() as $labor) {
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_AUDITOR) {
								if ($labor->value != $auditorZan) {
									$errors[] = "<b>Число часов аудиторных занятий за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов (".$auditorZan.") из нагрузки:
											лекции (".$sumLecture."), практики (".$sumPractice."), лабораторные работы (".$sumLabWork.")</b>";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL_THEORETIC_EDUCATION) {
								if ($labor->value != $teorObuch) {
									$errors[] = "<b>Всего теоретическое обучение за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов (".$teorObuch.") из нагрузки:
										аудиторные занятия (".$auditorZan."), самостоятельная работа (".$sumSelfWork.")</b>";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_EXAM_UNIT) {
								if ($labor->value != $sumExamUnit) {
									$errors[] = "Число часов зачётных единиц за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumExamUnit.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_KSR) {
								if ($labor->value != $sumKSR) {
									$errors[] = "Число часов КСР за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumKSR.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_WORK) {
								if ($labor->value != $sumCourseWork) {
									$errors[] = "Число часов курсовых работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCourseWork.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_COURSE_PROJECT) {
								if ($labor->value != $sumCourseProject) {
									$errors[] = "Число часов курсовых проектов за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCourseProject.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LAB_WORK) {
								if ($labor->value != $sumLabWork) {
									$errors[] = "Число часов лабораторных работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumLabWork.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_LECTURE) {
								if ($labor->value != $sumLecture) {
									$errors[] = "Число часов лекций за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumLecture.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_TOTAL) {
								if ($labor->value != $sumTotal) {
									$errors[] = "Трудоёмкость общая за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumTotal.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_PRACTICE) {
								if ($labor->value != $sumPractice) {
									$errors[] = "Число часов практик за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumPractice.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
								if ($labor->value != $sumSelfWork) {
									$errors[] = "Число часов самостоятельных работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumSelfWork.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_RGR) {
								if ($labor->value != $sumRGR) {
									$errors[] = "Число часов РГР за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumRGR.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_EXAMEN) {
								if ($labor->value != $sumExamen) {
									$errors[] = "Число часов на экзамен за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumExamen.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_CREDIT) {
								if ($labor->value != $sumCredit) {
									$errors[] = "Число часов на зачёт за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCredit.")";
								}
							}
							if ($term->number == $sect->id and $labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_CREDIT_WITH_MARK) {
								if ($labor->value != $sumCreditWithMark) {
									$errors[] = "Число часов на зачёт с оценкой за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCreditWithMark.")";
								}
							}
						}
					}
				}
			}
		}
		return $errors;
	}

}
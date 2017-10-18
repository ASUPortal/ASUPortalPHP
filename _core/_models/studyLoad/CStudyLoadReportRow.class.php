<?php
/**
 * Класс для отчёта сотрудника по учебной нагрузке
 */
class CStudyLoadReportRow extends CPerson {
	public $workloadSum = 0;
	public $groupsCountSum = 0;
	public $studentsCountSum = 0;
	public $hoursSumBase = 0;
	public $hoursSumAdditional = 0;
	public $hoursSumPremium = 0;
	public $hoursSumByTime = 0;
	public $diplCountWinter = 0;
	public $diplCountSummer = 0;
	
}
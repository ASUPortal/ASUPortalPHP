<?php
/**
 * Константы для вычисления нагрузки индивидуального плана
 */
interface CIndPlanPersonLoadConstants {
	// ID должности "Всего часов в индивидуальном плане" в справочнике должностей
	const TOTAL_HOURS_IN_INDIVIDUAL_PLAN = 27;
	// начало отсчёта плана нагрузки по осеннему семестру
	const PLAN_LOAD_AUTUMN_START = 0;
	// начало отсчёта плана нагрузки по весеннему семестру
	const PLAN_LOAD_SPRING_START = 6;
	// количество строк, пропускаемых при подсчёте суммы по нагрузке
	const COUNT_ROWS = 13;
}
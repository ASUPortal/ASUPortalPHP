<?php
/**
 * Константы видов работ нагрузок индивидуального плана
 */
interface CIndPlanPersonWorkType {
	/**
	 * Учебная нагрузка
	 */
	const STUDY_LOAD = 1;
	
	/**
	 * Учебная работа
	 */
	const STUDY_AND_METHODICAL_LOAD = 2;
	
	/**
	 * Научно-исследовательская работа
	 */
	const SCIENTIFIC_METHODICAL_LOAD = 3;
	
	/**
	 * Учебно-методическая, научно-методическая и воспитательная работа, 
	 * не включаемая в расчет штатной численности профессорско-преподавательского состава кафедр
	 */
	const STUDY_AND_EDUCATIONAL_LOAD = 4;
	
	/**
	 * Перечень научных и научно-методических работ
	 */
	const LIST_SCIENTIFIC_WORKS = 5;
	
	/**
	 * Записи об изменениях
	 */
	const CHANGE_RECORDS = 6;
	
	/**
	 * Организационно-методическая работа
	 */
	const ORGANIZATIONAL_AND_METHODICAL_LOAD = 7;
	
	/**
	 * Подготовка кадров высшей квалификации в аспирантуре
	 */
	const ASPIRANTS_LOAD = 8;
	
}
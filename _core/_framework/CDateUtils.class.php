<?php

class CDateUtils {
	
	/**
	 * Время, прошедшее от начальной даты $dateStart до конечной $dateEnd, в годах и месяцах
	 *
	 * @param $dateStart format 'd.m.Y'
	 * @param $dateEnd format 'd.m.Y'
	 * @return String
	 */
	public static function getTimeDifferenceString($dateStart, $dateEnd) {
		if ($dateStart !== "" and $dateEnd !== "") {
			$firstDateTimeObject = DateTime::createFromFormat('d.m.Y', $dateStart);
			$secondDateTimeObject = DateTime::createFromFormat('d.m.Y', $dateEnd);
			
			$delta = $secondDateTimeObject->diff($firstDateTimeObject);
			
			$days = $delta->format("%d");
			$year = $delta->format("%y");
			$month = $delta->format("%m");
			
			if ($year == 0 and $month == 0) {
				return $days." ".CUtils::getNumberInCase($days, "день", "дня", "дней");
			} elseif ($year == 0 and $month != 0) {
				return $month." ".CUtils::getNumberInCase($month, "месяц", "месяца", "месяцев");
			} elseif ($year != 0 and $month == 0) {
				return $year." ".CUtils::getNumberInCase($year, "год", "года", "лет");
			} else {
				return $year." ".CUtils::getNumberInCase($year, "год", "года", "лет")." ".$month." ".CUtils::getNumberInCase($month, "месяц", "месяца", "месяцев");
			}
		}
	}
	
}
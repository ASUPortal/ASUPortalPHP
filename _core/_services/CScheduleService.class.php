<?php

/**
 * Сервис по работе с расписанием
 *
 */
class CScheduleService {
    /**
     * Расписание пользователя по году и семестру
     * 
     * @param CUser $user - пользователь
     * @param CTerm $year - учебный год
     * @param CTerm $part - учебный семестр
     * @return CArrayList
     */
    public static function getScheduleUserByYearAndPart(CUser $user, CTerm $year, CTerm $part) {
    	$schedules = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_SCHEDULE, "user_id = ".$user->getId()." AND year = ".$year->getId()." AND month = ".$part->getId())->getItems() as $item) {
    		$schedule = new CSchedule($item);
    		$schedules->add($schedule->getId(), $schedule);
    	}
    	return $schedules;
    }
    
    /**
     * Расписание группы по году и семестру
     *
     * @param CStudentGroup $group - учебная группа
     * @param CTerm $year - учебный год
     * @param CTerm $part - учебный семестр
     * @return CArrayList
     */
    public static function getScheduleGroupByYearAndPart(CStudentGroup $group, CTerm $year, CTerm $part) {
    	$schedules = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_SCHEDULE, "grup = ".$group->getId()." AND year = ".$year->getId()." AND month = ".$part->getId())->getItems() as $item) {
    		$schedule = new CSchedule($item);
    		$schedules->add($schedule->getId(), $schedule);
    	}
    	return $schedules;
    }
    
    /**
     * Расписание по номеру дня недели и времени занятия
     *
     * @param CArrayList $schedules - лист расписаний
     * @param int $day - номер дня недели (1-пн, 2-вт, 3-ср, 4-чт, 5-пт, 6-сб)
     * @param int $number - время занятия (1='8.00-9.35'; 2='9.45-11.20'; 3='12.10-13.45'; 4='13.55-15.30'; 5='16.10-17.45'; 6='17.55-19.30'; 7='Вечерники')
     * @return CArrayList
     */
    public static function getScheduleByDayAndNumber(CArrayList $schedules, $day, $number) {
    	$items = new CArrayList();
    	foreach ($schedules as $schedule) {
    		if ($schedule->day == $day and $schedule->number == $number) {
    			$items->add($schedule->getId(), $schedule);
    		}
    	}
    	return $items;
    }
    
    /**
     * Расписание по преподавателю, номеру дня недели и времени занятия
     *
     * @param CTerm $year - учебный год
     * @param CTerm $part - учебный семестр
     * @param CUser $lecturer - преподаватель
     * @param int $day - номер дня недели (1-пн, 2-вт, 3-ср, 4-чт, 5-пт, 6-сб)
     * @param int $number - время занятия (1='8.00-9.35'; 2='9.45-11.20'; 3='12.10-13.45'; 4='13.55-15.30'; 5='16.10-17.45'; 6='17.55-19.30'; 7='Вечерники')
     * @return CArrayList
     */
    public static function getScheduleByLecturerDayAndNumber(CTerm $year, CTerm $part, CUser $lecturer, $day, $number) {
    	$schedules = new CArrayList();
    	foreach (CActiveRecordProvider::getWithCondition(TABLE_SCHEDULE, "year = ".$year->getId()." AND month = ".$part->getId()." AND day = ".$day." AND number = ".$number." AND user_id = ".$lecturer->getId())->getItems() as $item) {
    		$schedule = new CSchedule($item);
    		$schedules->add($schedule->getId(), $schedule);
    	}
    	return $schedules;
    }
    
    /**
     * Преподаватели для общего расписания по году и семестру
     *
     * @param CTerm $year - учебный год
     * @param CTerm $part - учебный семестр
     * @return CArrayList
     */
    public static function getLecturersWithSchedulesByYearAndPart(CTerm $year, CTerm $part) {
    	$lecturers = new CArrayList();
    	$query = new CQuery();
    	$query->select("distinct(schedule.user_id) as id, users.*")
	    	->from(TABLE_SCHEDULE." as schedule")
	    	->innerJoin(TABLE_USERS." as users", "schedule.user_id = users.id")
	    	->condition("schedule.year = ".$year->getId()." AND schedule.month = ".$part->getId())
	    	->order("users.FIO asc");
    	foreach($query->execute()->getItems() as $ar) {
    		$lecturer = new CUser(new CActiveRecord($ar));
    		$lecturers->add($lecturer->getId(), $lecturer);
    	}
    	return $lecturers;
    }
    
    /**
     * Учебные группы для общего расписания по году и семестру
     *
     * @param CTerm $year - учебный год
     * @param CTerm $part - учебный семестр
     * @return CArrayList
     */
    public static function getGroupsWithSchedulesByYearAndPart(CTerm $year, CTerm $part) {
    	$groups = new CArrayList();
    	$query = new CQuery();
    	$query->select("distinct(schedule.grup) as id, groups.*")
	    	->from(TABLE_SCHEDULE." as schedule")
	    	->innerJoin(TABLE_STUDENT_GROUPS." as groups", "schedule.grup = groups.id")
	    	->condition("schedule.year = ".$year->getId()." AND schedule.month = ".$part->getId())
	    	->order("groups.name asc");
    	foreach($query->execute()->getItems() as $ar) {
    		$group = new CStudentGroup(new CActiveRecord($ar));
    		$groups->add($group->getId(), $group);
    	}
    	return $groups;
    }
}
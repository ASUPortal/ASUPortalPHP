<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 02.09.13
 * Time: 17:47
 * To change this template use File | Settings | File Templates.
 */

interface IWorktypeCompletion {
    /**
     * Планируемое количество часов по указанному виду работы
     * Функция должна возвращать количество часов или 0
     *
     * @param CPerson $person
     * @param CTerm $year
     * @return int
     */
    public function getHoursPlanned(CPerson $person, CTerm $year);

    /**
     * Выполнен ли план.
     * Функция должна возвращать true/false
     *
     * @param CPerson $person
     * @param CTerm $year
     * @return bool
     */
    public function isCompleted(CPerson $person, CTerm $year);
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.09.12
 * Time: 9:50
 * To change this template use File | Settings | File Templates.
 */
interface IRatingIndexCalculationInterface {
    public function calculateForPerson(CPerson $person, ICalculatable $method);
    public function calculateAverage(ICalculatable $method);
}

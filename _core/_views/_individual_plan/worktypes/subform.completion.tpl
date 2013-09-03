<pre>
    public class CWorktypeCompletionClass implements IWorktypeCompletion{
        /**
         * Планируемое количество часов по указанному виду работы
         * Функция должна возвращать количество часов или 0
         *
         * @param CPerson $person
         * @param CTerm $year
         * @return int
         */
        public function getHoursPlanned(CPerson $person, CTerm $year) {
            {CHtml::activeTextBox("completion_planned", $work, "", "", 'style="font-family: Monaco, Menlo, Consolas, \'Courier New\', monospace; height: 200px; "')}
        }

        /**
         * Выполнен ли план.
         * Функция должна возвращать true/false
         *
         * @param CPerson $person
         * @param CTerm $year
         * @return bool
         */
        public function isCompleted(CPerson $person, CTerm $year) {
            {CHtml::activeTextBox("completion_completed", $work, "", "", 'style="font-family: Monaco, Menlo, Consolas, \'Courier New\', monospace; height: 200px; "')}
        }
    }
</pre>
<h3>7.1. Основная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=1&plan_id={$plan->getId()}", $plan)}

<h3>7.2. Дополнительная литература</h3>

{CHtml::activeComponent("workplanliterature.php?type=2&plan_id={$plan->getId()}", $plan)}

<h3>7.3. Интернет-ресурсы</h3>

{CHtml::activeComponent("workplanliterature.php?type=3&plan_id={$plan->getId()}", $plan)}
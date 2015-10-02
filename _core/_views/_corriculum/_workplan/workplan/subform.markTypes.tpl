<h3>6.1. Перечень оценочных средств</h3>

{CHtml::activeComponent("workplanmarktypes.php?plan_id={$plan->getId()}", $plan)}

<h3>6.2. Балльно-рейтинговая система</h3>

{CHtml::activeComponent("workplanbrs.php?plan_id={$plan->getId()}", $plan)}

<h3>Фонд оценочных средств</h3>

{CHtml::activeComponent("workplanfundmarktypes.php?plan_id={$plan->getId()}", $plan)}
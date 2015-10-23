<h3>6.1. Перечень оценочных средств</h3>

{CHtml::activeComponent("workplanmarktypes.php?plan_id={$plan->getId()}", $plan)}

<h3>6.2. Балльно-рейтинговая система</h3>

{CHtml::activeComponent("workplanbrs.php?plan_id={$plan->getId()}", $plan)}

<h3>6.3. Фонд оценочных средств</h3>

{CHtml::activeComponent("workplanfundmarktypes.php?plan_id={$plan->getId()}", $plan)}

<h3>6.4. Вопросы к экзамену</h3>

{CHtml::activeComponent("workplanexamquestions.php?type=1&plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки</h4>

{CHtml::activeComponent("workplancriteriaofevaluation.php?type=1&plan_id={$plan->getId()}", $plan)}

<h3>6.5. Вопросы к зачёту</h3>

{CHtml::activeComponent("workplanexamquestions.php?type=2&plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки</h4>

{CHtml::activeComponent("workplancriteriaofevaluation.php?type=2&plan_id={$plan->getId()}", $plan)}

<h3>6.6. Типовые оценочные материалы</h3>

{CHtml::activeComponent("workplanevaluationmaterials.php?plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки</h4>

{CHtml::activeComponent("workplancriteriaofevaluation.php?type=3&plan_id={$plan->getId()}", $plan)}
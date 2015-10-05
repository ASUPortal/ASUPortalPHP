<h3>6.1. Перечень оценочных средств</h3>

{CHtml::activeComponent("workplanmarktypes.php?plan_id={$plan->getId()}", $plan)}

<h3>6.2. Балльно-рейтинговая система</h3>

{CHtml::activeComponent("workplanbrs.php?plan_id={$plan->getId()}", $plan)}

<h3>6.3. Фонд оценочных средств</h3>

{CHtml::activeComponent("workplanfundmarktypes.php?plan_id={$plan->getId()}", $plan)}

<h3>6.4. Вопросы к экзамену (зачёту)</h3>

{CHtml::activeComponent("workplanqeustionstoexamination.php?plan_id={$plan->getId()}", $plan)}

<h3>6.5. Критерии оценки</h3>

{CHtml::activeComponent("workplanwayofestimation.php?plan_id={$plan->getId()}", $plan)}

<h3>6.6. Типовые оценочные материалы</h3>

{CHtml::activeComponent("workplantypicalestimatedmaterials.php?plan_id={$plan->getId()}", $plan)}
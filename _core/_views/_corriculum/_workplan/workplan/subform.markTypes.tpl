<h3>5. Фонд оценочных средств</h3>
{CHtml::activeComponent("workplanfundmarktypes.php?plan_id={$plan->getId()}", $plan, ["defaultAction" => "view"])}

<h3>Балльно-рейтинговая система</h3>
{CHtml::activeComponent("workplanbrs.php?plan_id={$plan->getId()}", $plan)}

<h3>Вопросы к экзамену</h3>
{CHtml::activeComponent("workplanexamquestions.php?type=1&plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки экзамена</h4>
{CHtml::activeComponent("workplancriteriaofevaluation.php?type=1&plan_id={$plan->getId()}", $plan)}

<h3>Вопросы к зачёту</h3>
{CHtml::activeComponent("workplanexamquestions.php?type=2&plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки зачёта</h4>
{CHtml::activeComponent("workplancriteriaofevaluation.php?type=2&plan_id={$plan->getId()}", $plan)}

<h3>5.1. Типовые оценочные материалы</h3>
{CHtml::activeComponent("workplanevaluationmaterials.php?plan_id={$plan->getId()}", $plan)}

<h4>Критерии оценки материалов</h4>
{CHtml::activeComponent("workplancriteriaofevaluation.php?type=3&plan_id={$plan->getId()}", $plan)}

{*
<h3>Перечень оценочных средств</h3>
{CHtml::activeComponent("workplanmarktypes.php?plan_id={$plan->getId()}", $plan)}
*}

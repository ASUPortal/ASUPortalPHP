<h3>3. Требования к результатам освоения содержания дисциплины</h3>
<h4>Входные компетенции</h4>
{CHtml::activeComponent("workplancompetentionsinputs.php?plan_id={$plan->getId()}", $plan)}

<h4>Формируемые компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?plan_id={$plan->getId()}", $plan)}

<h4>Исходящие компетенции</h4>
{CHtml::activeComponent("workplancompetentionsouts.php?plan_id={$plan->getId()}", $plan)}
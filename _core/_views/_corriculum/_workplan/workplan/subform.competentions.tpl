<h3>3. Требования к результатам освоения содержания дисциплины</h3>
<h4>Входные компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?type=1&plan_id={$plan->getId()}", $plan, ["withoutScripts" => "true"])}

<h4>Формируемые компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?type=0&plan_id={$plan->getId()}", $plan, ["withoutScripts" => "true"])}

<h4>Исходящие компетенции</h4>
{CHtml::activeComponent("workplancompetentions.php?type=2&plan_id={$plan->getId()}", $plan, ["withoutScripts" => "true"])}
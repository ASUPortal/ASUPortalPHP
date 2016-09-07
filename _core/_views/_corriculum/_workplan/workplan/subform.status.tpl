<div class="control-group">
    {CHtml::activeLabel("comment_file", $plan)}
    <div class="controls">
    {CHtml::activeDropDownList("comment_file", $plan, CTaxonomyManager::getTaxonomy("comment_file_workplan")->getTermsList())}
    {CHtml::error("comment_file", $plan)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("status_on_portal", $plan)}
    <div class="controls">
    {CHtml::activeDropDownList("status_on_portal", $plan, CTaxonomyManager::getTaxonomy("status_workplan_on_portal")->getTermsList())}
    {CHtml::error("status_on_portal", $plan)}
    </div>
</div>

<h4>Статус заполненности полей рабочей программы по шаблонам</h4>
{CHtml::activeComponent("workplanstatus.php?id={$plan->getId()}", $plan)}
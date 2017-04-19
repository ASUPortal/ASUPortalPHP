<div class="control-group">
    {CHtml::activeLabel("commission[title]", $form)}
	<div class="controls">
    {CHtml::activeTextField("commission[title]", $form)}
    {CHtml::error("commission[title]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[comment]", $form)}
	<div class="controls">
    {CHtml::activeTextBox("commission[comment]", $form)}
    {CHtml::error("commission[comment]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[year_id]", $form)}
	<div class="controls">
    {CHtml::activeDropDownList("commission[year_id]", $form, CTaxonomyManager::getYearsList())}
    {CHtml::error("commission[year_id]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[secretar_id]", $form)}
	<div class="controls">
    {CHtml::activeLookup("commission[secretar_id]", $form, "staff")}
    {CHtml::error("commission[secretar_id]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[order_id]", $form)}
	<div class="controls">
    {CHtml::activeDropDownList("commission[order_id]", $form, CStaffManager::getUsatuSEBOrdersList())}
    {CHtml::error("commission[order_id]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[manager_id]", $form)}
	<div class="controls">
    {CHtml::activeLookup("commission[manager_id]", $form, "staff")}
    {CHtml::error("commission[manager_id]", $form)}
</div></div>

{*
<div class="control-group">
    {CHtml::activeLabel("commission[members]", $form)}
	<div class="controls">
    {CHtml::activeLookup("commission[members]", $form, "staff", true)}
    {CHtml::error("commission[members]", $form)}
</div></div>
*}
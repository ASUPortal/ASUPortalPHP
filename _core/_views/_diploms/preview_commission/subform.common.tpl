<div class="control-group">
    {CHtml::activeLabel("commission[name]", $form)}
	<div class="controls">
    {CHtml::activeTextField("commission[name]", $form)}
    {CHtml::error("commission[name]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[comment]", $form)}
	<div class="controls">
    {CHtml::activeTextBox("commission[comment]", $form)}
    {CHtml::error("commission[comment]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[date_act]", $form)}
	<div class="controls">
    {CHtml::activeDateField("commission[date_act]", $form)}
    {CHtml::error("commission[date_act]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[secretary_id]", $form)}
	<div class="controls">
    {CHtml::activeLookup("commission[secretary_id]", $form, "staff")}
    {CHtml::error("commission[secretary_id]", $form)}
</div></div>

<div class="control-group">
    {CHtml::activeLabel("commission[members]", $form)}
	<div class="controls">
    {CHtml::activeLookup("commission[members]", $form, "staff", true)}
    {CHtml::error("commission[members]", $form)}
</div></div>

{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="#printDialog" data-toggle="modal">
    	<center>
        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        	Печать по шаблону
    	</center></a>
</p>

<div id="printDialog" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        {CHtml::printOnTemplate("formset_all_persons")}
    </div>
</div>

{include file="_staff/person/printGroupOnTemplate.tpl"}

<div class="menu_item_container">
	<a href="{$web_root}_modules/_print/?action=ShowForms&template={$templateList}" asu-action="flow">
		{if !is_null($formsetList)}
			{$var = $formsetList->computeTemplateVariables()}
			{foreach $var as $key=>$value}
				<div asu-type="flow-property" name="{$key}" value="{$value}"></div>
			{/foreach}
		{/if}
		<center>
			<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
				Печать списка сотрудников
		</center>
	</a>
</div>
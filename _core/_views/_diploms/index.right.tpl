{CHtml::displayActionsMenu($_actions_menu)}

{include file="_printGroupOnTemplate.tpl"}


<div class="menu_item_container">
	<a href="{$web_root}_modules/_print/?action=ShowForms&template={$templateThemes}" asu-action="flow">
		{if !is_null($formsetThemes)}
			{$var = $formsetThemes->computeTemplateVariables()}
			{foreach $var as $key=>$value}
				<div asu-type="flow-property" name="{$key}" value="{$value}"></div>
			{/foreach}
		{/if}
		<center>
			<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
				Печать по шаблону списка
		</center>
	</a>
</div>
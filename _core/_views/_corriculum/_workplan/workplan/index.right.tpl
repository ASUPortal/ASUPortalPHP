{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addFromView">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить рабочую программу
        </center></a>
</p>

{include file="_corriculum/_plan/_printGroupOnTemplateWorkplans.tpl"}

<div class="menu_item_container">
	<a href="{$web_root}_modules/_print/?action=ShowForms&template={$template}" asu-action="flow">
		{if !is_null($formset)}
			{$var = $formset->computeTemplateVariables()}
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

<script>
	function selectedForCorriculumChange() {
		var items = new Array();
		jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
			items.push(jQuery(value).val());
		});
		window.location.href = "workplans.php?action=corriculumToChange&selected=" + items.join(":");
	}
	function selectedForCorriculumCopy() {
		var items = new Array();
		jQuery.each(jQuery("input[name='selectedDoc[]']:checked"), function(key, value){
			items.push(jQuery(value).val());
		});
		window.location.href = "workplans.php?action=corriculumToCopy&selected=" + items.join(":");
	}
</script>    
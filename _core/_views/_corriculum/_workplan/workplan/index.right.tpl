{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addFromView" asu-action="flow">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить рабочую программу
        </center></a>
</p>

{include file="_corriculum/_plan/_printGroupOnTemplateWorkplans.tpl"}

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
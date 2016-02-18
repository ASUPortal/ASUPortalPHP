{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addFromView" asu-action="flow">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить рабочую программу
        </center></a>
</p>

<p>
    <a href="workplans.php?action=corriculumToChange" asu-action="flow">
        <center>
        <div asu-type="flow-property" name="selected" value="selectedInView"></div>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-redo.png"><br>
            Сменить учебный план
        </center></a>
</p>

<p>
    <a href="workplans.php?action=corriculumToCopy" asu-action="flow">
        <center>
        <div asu-type="flow-property" name="selected" value="selectedInView"></div>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
            Копировать в другой учебный план
        </center></a>
</p>

{include file="_printGroupOnTemplate.tpl"}
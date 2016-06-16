<table class="table table-bordered table-hover table-condensed">
    <tr>
        <td>
            <a href="index.php?action=index"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
		        Назад
		    </center></a>
        </td>
        <td>
            <a href="index.php?action=edit&id={$corriculum->getId()}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/apps/accessories-text-editor.png"><br>
		        Редактировать
		    </center></a>
        </td>
        <td>
            <a href="cycles.php?action=add&id={$corriculum->id}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
		        Добавить цикл
		    </center></a>
        </td>
        <td>
            <a href="index.php?action=copy&id={$corriculum->id}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
		        Копировать план
		    </center></a>
        </td>
        <td>
            <a href="workplans.php?action=index&corriculumId={$corriculum->id}"><center>
		        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
		        Рабочие программы учебного плана
		    </center></a>
        </td>
        <td>
            {CHtml::displayActionsMenu($_actions_menu)}
        </td>
        <td width="200">
            {include file="_corriculum/_plan/_printGroupOnTemplateCorriculumDisciplines.tpl"}
        </td>
        <td width="180">
            {include file="_corriculum/_plan/_printGroupOnTemplateWorkplans.tpl"}
        </td>
    </tr>
</table>
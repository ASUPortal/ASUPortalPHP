<p>
    <a href="?action=index"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="?action=edit&id={$corriculum->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/accessories-text-editor.png"><br>
        Редактировать
    </center></a>
</p>

<p>
    <a href="cycles.php?action=add&id={$corriculum->id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
        Добавить цикл
    </center></a>
</p>

<p>
    <a href="?action=copy&id={$corriculum->id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-copy.png"><br>
        Копировать план
    </center></a>
</p>
{CHtml::displayActionsMenu($_actions_menu)}
{include file="_printGroupOnTemplate.tpl"}
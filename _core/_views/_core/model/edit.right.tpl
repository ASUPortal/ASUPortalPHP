<p>
    <a href="?action=index"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="fields.php?action=add&id={$model->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить поле
        </center></a>
</p>

<p>
    <a href="tasks.php?action=add&id={$model->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить задачу
        </center></a>
</p>

{if ($model->isExportable() == "1")}
<p>
    <a href="models.php?action=export&id={$model->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-print-preview.png">
            Выгрузить в поиск
        </center></a>
</p>
{/if}

<p>
    <a href="?action=importFields&id={$model->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save.png">
            Импортировать поля
        </center></a>
</p>
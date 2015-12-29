{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="index.php?action=addFromUrlForDiscipline&discipline_id={CRequest::getInt("id")}" asu-action="flow">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save.png"><br>
            Добавить литературу из библиотеки
        </center></a>
</p>
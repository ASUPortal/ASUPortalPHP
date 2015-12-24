{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addLiterature&plan_id={CRequest::getInt("id")}" asu-action="flow">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить литературу
        </center></a>
</p>
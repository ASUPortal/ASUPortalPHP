{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="workplans.php?action=addFromUrl&plan_id={CRequest::getInt("id")}" asu-action="flow">
        <center>
        <div asu-type="flow-property" name="selected" value="selectedInView"></div>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить литературу из библиотеки
        </center></a>
</p>
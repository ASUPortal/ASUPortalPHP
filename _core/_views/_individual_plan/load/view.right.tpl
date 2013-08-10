<p>
    <a href="load.php?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="#myModal" data-toggle="modal">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить
        </center></a>
</p>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Добавление записи в план</h3>
    </div>
    <div class="modal-body">
        <p><a href="load/organizational.php?action=add&id={$person->getId()}">Учебно и организационно-методическая работа</a></p>
        <p><a href="load/sciences.php?action=add&id={$person->getId()}">Научно-методическая и госбюджетая научно-исследовательская работа</a></p>
        <p><a href="load/educations.php?action=add&id={$person->getId()}">Учебно-воспитательная работа</a></p>
        <p><a href="load/publications.php?action=add&id={$person->getId()}">Научная работа</a></p>
        <p><a href="load/changes.php?action=add&id={$person->getId()}">Изменение в годовом индивидуальном плане</a></p>
        <p><a href="load/conclusions.php?action=add&id={$person->getId()}">Заключение заведующего кафедрой</a></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
    </div>
</div>
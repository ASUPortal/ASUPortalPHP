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

<p>
    <a href="#printDialog" data-toggle="modal"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        Печать по шаблону
    </center></a>
</p>

<div id="printDialog" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <b>Печать для всех дисциплин</b>

        {CHtml::printGroupOnTemplate("formset_corriculum_disciplines", false, "{$web_root}_modules/_corriculum/index.php", "JSONGetDisciplines", $corriculum->getId())}
    </div>
</div>

<div id="groupPrintDialog"  class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        <div class="progress progress-striped active">
            <div class="bar" id="progressbar" style="width: 0%;"></div>
        </div>
        <div id="statusbar">Подождите, идет формирование архива</div>
    </div>
</div>


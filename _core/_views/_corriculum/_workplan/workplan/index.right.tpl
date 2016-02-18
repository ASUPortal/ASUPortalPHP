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
        <b>Печать для всех рабочих программ</b>

        {CHtml::printGroupOnTemplate("formset_workplans", true)}
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
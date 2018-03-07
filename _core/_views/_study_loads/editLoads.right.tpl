{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="#printDialog" data-toggle="modal">
    	<center>
        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        	Печать по карточке сотрудника
    	</center></a>
</p>

<div id="printDialog" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        {CHtml::printOnTemplate("formset_person_from_study_load")}
    </div>
</div>
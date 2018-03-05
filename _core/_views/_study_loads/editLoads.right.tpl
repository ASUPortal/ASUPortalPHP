{CHtml::displayActionsMenu($_actions_menu)}

<p>
    <a href="#printDialog1" data-toggle="modal">
    	<center>
        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        	Печать по шаблону
    	</center></a>
</p>

<div id="printDialog1" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        {CHtml::printOnTemplate("formset_study_loads")}
    </div>
</div>

<p>
    <a href="#printDialog2" data-toggle="modal">
    	<center>
        	<img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png"><br>
        	Печать по карточке сотрудника
    	</center></a>
</p>

<div id="printDialog2" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Печать по шаблону</h3>
    </div>
    <div class="modal-body">
        {CHtml::printOnTemplate("formset_person_from_study_load")}
    </div>
</div>
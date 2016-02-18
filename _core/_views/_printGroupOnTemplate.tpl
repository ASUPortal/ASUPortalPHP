<p>
    <a href="#printDialog" data-toggle="modal">
    	<center>
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
        <b>Массовая печать</b>

        {CHtml::printGroupOnTemplate($template, $selectedDoc, $url, $action, $id)}
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
<p>
    <a href="?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png" /><br>
            В начало
        </center></a>
</p>

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
        {CHtml::printOnTemplate("examination_tickets")}
    </div>
</div>
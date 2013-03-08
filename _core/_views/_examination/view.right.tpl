<p>
    <a href="?action=index">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/edit-undo.png" /><br>
            В начало
        </center></a>
</p>

<p>
    <a href="#" onclick="jQuery('#dialog').dialog(); return false; ">
        <center>
            <img src="{$web_root}images/tango/32x32/devices/printer.png" /><br>
            Печать по шаблону
        </center></a>
</p>

<div id="dialog" style="display: none; " title="Выберите шаблон для печати">
    {CHtml::printOnTemplate("examination_tickets")}
</div>
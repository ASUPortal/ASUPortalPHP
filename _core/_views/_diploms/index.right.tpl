{CHtml::displayActionsMenu($_actions_menu)} 
<p>
    <a href="#" onclick="jQuery('#dialog').dialog(); return false; ">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png" /><br>
            Печать по шаблону
        </center></a>
</p>

<div id="dialog" style="display: none; " title="Выберите шаблон для печати">
    {CHtml::printOnTemplate("formset_diploms")}
</div>	
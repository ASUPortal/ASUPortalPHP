<p>
    <a href="{$web_root}diploms_view.php"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

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
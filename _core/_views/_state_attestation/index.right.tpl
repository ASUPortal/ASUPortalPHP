{if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
    <p>
        <a href="{$web_root}_modules/_dashboard/">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                На рабочий стол
            </center></a>
    </p>
{/if}{/if}

<p>
    <a href="?action=add"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить комиссию
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
    {CHtml::printOnTemplate("formset_state_attestation_group")}
</div>
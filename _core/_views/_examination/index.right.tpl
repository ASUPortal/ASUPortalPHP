{if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
<p>
    <a href="{$web_root}_modules/_dashboard/">
        <center>
            <img src="{$web_root}images/tango/32x32/apps/preferences-system-session.png"><br>
            На рабочий стол
        </center></a>
</p>
{/if}{/if}

<p>
    <a href="?action=my">
        <center>
            <img src="{$web_root}images/tango/32x32/devices/media-floppy.png"><br>
            Мои билеты
        </center></a>
</p>

<p>
    <a href="?action=add">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/list-add.png">
            Добавить вопрос
        </center></a>
</p>

<p>
    <a href="?action=addGroup">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/list-add.png">
            Групповое добавление
        </center></a>
</p>

<p>
    <a href="?action=generate">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/view-refresh.png">
            Генерация билетов
    </center></a>
</p>

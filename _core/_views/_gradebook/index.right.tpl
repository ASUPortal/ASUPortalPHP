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
    <a href="index.php?action=add">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/list-add.png"><br>
            Добавить
        </center></a>
</p>

<p>
    <a href="index.php?action=addGroup">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/mail-reply-all.png"><br>
            Групповое добавление
        </center></a>
</p>

<p>
    <a href="index.php?action=myGradebooks">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/format-justify-fill.png"><br>
            Мои журналы
        </center></a>
</p>
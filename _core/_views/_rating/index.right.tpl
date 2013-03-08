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
    <a href="indexes.php">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/address-book-new.png">
            Показатели
    </center></a>
</p>

<p>
    <a href="persons.php">
        <center>
            <img src="{$web_root}images/tango/32x32/apps/system-users.png">
            Показатели преподавателей
        </center></a>
</p>

<p>
    <a href="#" onclick="redrawChart(); return false; ">
        <center>
            <img src="{$web_root}images/tango/32x32/actions/view-refresh.png">
            Обновить диаграмму
        </center></a>
</p>
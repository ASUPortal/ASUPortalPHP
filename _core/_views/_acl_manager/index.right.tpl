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
    <a href="groups.php"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/system-users.png">
        Группы пользователей
    </center></a>
</p>

<p>
    <a href="tables.php"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-windows.png">
        Таблицы контроля доступа
    </center></a>
</p>
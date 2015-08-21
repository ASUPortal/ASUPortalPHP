{if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
    <p>
        <a href="{$web_root}_modules/_dashboard/index.php">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                На рабочий стол
            </center></a>
    </p>
{/if}{/if}

<p>
    <a href="worktypes.php?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-windows.png"><br>
            Виды работ
        </center></a>
</p>

<p>
    <a href="load.php?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-windows.png"><br>
            Учебная нагрузка
        </center></a>
</p>
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
        Добавить студента
    </center></a>
</p>

<p>
    <a href="{$web_root}diploms_view.php"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/devices/network-wired.png">
        Дипломы
    </center></a>
</p>

<p>
    <a href="?action=import"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save.png">
        Импорт
    </center></a>
</p>
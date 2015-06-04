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
    <a href="?action=index">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="{$web_root}p_lecturers.php?onget=1&idlect={$biography->user_id}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/format-justify-fill.png"><br>
            Анкета
        </center></a>
</p>
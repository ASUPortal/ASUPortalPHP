{if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
    <p>
        <a href="{$web_root}_modules/_dashboard/">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                На рабочий стол
            </center></a>
    </p>
{/if}{/if}

{if ($biographys->getCount() != 0)}
<p>
    <a href="{$web_root}_modules/_lecturers/index.php?action=view&id={$biography->user_id}">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/format-justify-fill.png"><br>
            Анкета
        </center></a>
</p>
{/if}
{if (CSession::isAuth()) and !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
    <p>
        <a href="{$web_root}_modules/_dashboard/">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                На рабочий стол
            </center></a>
    </p>
{/if}{/if}
{if (CSession::isAuth() and CSession::getCurrentUser()->status=='преподаватель')}
<p>
    <a href="{$web_root}_modules/_biography/"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить биографию
        </center></a>
</p>
{/if}

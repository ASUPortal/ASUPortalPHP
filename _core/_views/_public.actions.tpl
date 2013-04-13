{if (CSession::isAuth())}
    {if !is_null(CSession::getCurrentUser()->getPersonalSettings())}{if CSession::getCurrentUser()->getPersonalSettings()->isDashboardEnabled()}
        <div class="asu_right_home_block">
            <a href="{$web_root}_modules/_dashboard/">
                <center>
                    <img src="{$web_root}images/{$icon_theme}/32x32/apps/preferences-system-session.png"><br>
                    На рабочий стол
                </center></a>
        </div>
    {/if}{/if}{/if}

{if (CSession::isAuth())}
    {if (CSession::getCurrentUser()->hasRole("news_add"))}
        <div class="asu_right_home_block">
            <a href="{$web_root}_modules/_news/?action=add">
                <center>
                    <img src="{$web_root}images/{$icon_theme}/32x32/actions/appointment-new.png"><br>
                    Добавить новость
                </center></a>
        </div>
    {/if}
{/if}

{if (CSession::isAuth())}
    {if (CSession::getCurrentUser()->hasRole("pages_add"))}
        <div class="asu_right_home_block">
            <a href="{$web_root}_modules/_pages/admin.php">
                <center>
                    <img src="{$web_root}images/{$icon_theme}/32x32/actions/bookmark-new.png"><br>
                    Мои страницы
                </center></a>
        </div>
    {/if}
{/if}
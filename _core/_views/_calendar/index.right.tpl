{if (CSession::isAuth())}
    <p>
        {if CRequest::getInt("calendar_id") != 0}
            <a href="?action=add&calendar_id={CRequest::getInt("calendar_id")}">
        {else}
            <a href="?action=add">
        {/if}
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png">
            Добавить
        </center></a>
    </p>
{/if}
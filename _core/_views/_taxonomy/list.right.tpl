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
    <a href="?action=addTaxonomy"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/view-refresh.png">
        Добавить таксономию
    </center></a>
</p>

<p>
    <a href="?action=addLegacyTaxonomy"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/document-save-as.png">
        Зарегистрировать унаследованную таксономию
    </center></a>
</p>
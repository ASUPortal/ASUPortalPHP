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
    <a href="?action=index">
        <img src="{$web_root}images/tango/32x32/actions/edit-undo.png"><br />
        Все таксономии
    </a>
</p>

<p>
    <a href="?action=add&taxonomy_id={$taxonomy->getId()}"><center>
        <img src="{$web_root}images/tango/32x32/actions/list-add.png">
        Добавить термин
    </center></a>
</p>

<p>
    <a href="?action=addTaxonomy"><center>
        <img src="{$web_root}images/tango/32x32/actions/view-refresh.png">
        Добавить таксономию
    </center></a>
</p>
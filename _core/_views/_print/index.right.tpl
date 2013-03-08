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
    <a href="formset.php?action=index">
        <center>
            <img src="{$web_root}images/tango/32x32/apps/preferences-system-windows.png"><br>
            Наборы шаблонов
        </center></a>
</p>

<p>
    <a href="form.php?action=index">
        <center>
            <img src="{$web_root}images/tango/32x32/mimetypes/x-office-drawing-template.png"><br>
            Шаблоны документов
        </center></a>
</p>

<p>
    <a href="field.php?action=index">
        <center>
            <img src="{$web_root}images/tango/32x32/mimetypes/text-x-script.png"><br>
            Описатели полей
        </center></a>
</p>
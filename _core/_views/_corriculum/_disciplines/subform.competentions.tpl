<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Компетенция</th>
        <th>ЗУН</th>
    </tr>
    {foreach $discipline->competentions->getItems() as $comp}
        <tr>
            <td>{counter}</td>
            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить компетенцию')) { location.href='competentions.php?action=delete&id={$comp->getId()}'; }; return false;"></a></td>
            <td>
                {if !is_null($comp->competention)}
                    <a href="competentions.php?action=edit&id={$comp->getId()}">{$comp->competention->getValue()}</a>
                {/if}
            </td>
            <td>
                {if !is_null($comp->knowledge)}
                    {$comp->knowledge->getValue()}
                {/if}
            </td>
        </tr>
    {/foreach}
</table>
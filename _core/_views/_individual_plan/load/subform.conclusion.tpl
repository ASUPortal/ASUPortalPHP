{if $load->getConclusions()->getCount() == 0}
    Заключения нет
{else}
    <table class="table">
        {foreach $load->getConclusions()->getItems() as $c}
            <tr>
                <td width="16">{counter start=1}</td>
                <td width="16"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить заключение?')) { location.href='load/conclusions.php?action=delete&id={$c->getId()}'; }; return false;"></a></td>
                <td width="16"><a href="load/conclusions.php?action=edit&id={$c->getId()}" class="icon-pencil"></a></td>
                <td>{$c->msg}</td>
            </tr>
        {/foreach}
    </table>
{/if}
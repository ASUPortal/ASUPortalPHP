{if $load->getPublications()->getCount() == 0}
    Нет публикаций
{else}
    <table class="table">
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>{CHtml::tableOrder("paper_id", $load->getPublications()->getFirstItem())}</th>

        </tr>
        {foreach $load->getPublications()->getItems() as $c}
            <tr>
                <td width="16"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить публикацию?')) { location.href='load/publications.php?action=delete&id={$c->getId()}'; }; return false;"></a></td>
                <td width="16">{counter start=1}</td>
                <td width="16"><a href="load/publications.php?action=edit&id={$c->getId()}" class="icon-pencil"></a></td>
                <td>
                    {if !is_null($c->publication)}
                        {$c->publication->name}
                    {/if}
                </td>
            </tr>
        {/foreach}
    </table>
{/if}
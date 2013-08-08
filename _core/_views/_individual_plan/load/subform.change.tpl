{if $load->getChanges()->getCount() == 0}
    Нет записей об изменении в плане
    {else}
    <table class="table">
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>{CHtml::tableOrder("razdel", $load->getChanges()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("izmenenie", $load->getChanges()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("zav", $load->getChanges()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("prep", $load->getChanges()->getFirstItem())}</th>
            <th>{CHtml::tableOrder("id_otmetka", $load->getChanges()->getFirstItem())}</th>
        </tr>
        {foreach $load->getChanges()->getItems() as $c}
            <tr>
                <td width="16"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить изменение?')) { location.href='load/changes.php?action=delete&id={$c->getId()}'; }; return false;"></a></td>
                <td width="16">{counter start=1}</td>
                <td width="16"><a href="load/changes.php?action=edit&id={$c->getId()}" class="icon-pencil"></a></td>
                <td>{$c->razdel}</td>
                <td>{$c->izmenenie}</td>
                <td>{$c->zav}</td>
                <td>{$c->prep}</td>
                <td>{$c->getMark()}</td>
            </tr>
        {/foreach}
    </table>
{/if}
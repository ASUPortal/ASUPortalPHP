<table width="100%" cellpadding="2" cellspacing="0" border="1">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Вид нагрузки</th>
        <th>Величина</th>
    </tr>
    {foreach $discipline->labors->getItems() as $labor}
    <tr>
        <td>{counter}</td>
        <td><a href="#" onclick="if (confirm('Действительно удалить вид занятия {if !is_null($labor->type)}{$labor->type->getValue()}{/if}')) { location.href='labors.php?action=del&id={$labor->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td>
            {if !is_null($labor->type)}
                <a href="labors.php?action=edit&id={$labor->getId()}">{$labor->type->getValue()}</a>
            {/if}
        </td>
        <td>{$labor->value}</td>
    </tr>
    {/foreach}
</table>
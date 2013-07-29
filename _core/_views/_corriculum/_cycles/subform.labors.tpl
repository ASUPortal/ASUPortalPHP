{function name=print_discipline_row level=0}
    <tr>
        <td align="center">
            {$discipline->ordering}
        </td>
        <td>
            {if !is_null($discipline->cycle)}
                {$discipline->cycle->number}.
            {/if}
            {if $discipline->parent_id !== "0"}
                {if !is_null($discipline->parent)}
                    {$discipline->parent->ordering}
                {/if}
                .{$discipline->ordering}
            {else}
                {$discipline->ordering}
            {/if}
        </td>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить дисциплину {if !is_null($discipline->discipline)}{$discipline->discipline->getValue()}{/if}')) { location.href='disciplines.php?action=del&id={$discipline->id}'; }; return false;"></a></td>
        <td>
            {if !is_null($discipline->discipline)}
                {for $i=1 to $level}
                    &nbsp;&nbsp;
                {/for}
                <a href="disciplines.php?action=edit&id={$discipline->getId()}">{$discipline->discipline->getValue()}</a>
            {/if}
        </td>
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborValue()}
            {/if}
        </td>
        {foreach $labors->getItems() as $key=>$value}
        <td>
            {if !is_null($discipline->getLaborByType($key))}
                {$discipline->getLaborByType($key)->value}
            {else}
                &nbsp;
            {/if}
        </td>
        {/foreach}
    </tr>
{/function}

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th rowspan="2">&nbsp;</th>
        <th rowspan="2">#</th>
        <th rowspan="2">&nbsp;</th>
        <th rowspan="2">Наименование</th>
        <th colspan="{$labors->getCount() + 1}">Распределение объема учебной нагрузки по видам занятий</th>
    </tr>
    <tr>
        <th>Всего</th>
        {foreach $labors->getItems() as $labor}
            <td>
                {if !is_null($labor->type)}
                    {$labor->type->getValue()}
                {/if}
            </td>
        {/foreach}
    </tr>
    {foreach $cycle->disciplines->getItems() as $discipline}
        {print_discipline_row discipline=$discipline level=0}
            {foreach $discipline->children->getItems() as $child}
                {print_discipline_row discipline=$child level=1}
            {/foreach}
    {/foreach}
</table>
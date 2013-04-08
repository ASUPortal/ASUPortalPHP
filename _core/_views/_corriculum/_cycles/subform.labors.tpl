<table width="100%" cellpadding="2" cellspacing="0" border="1">
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
    <tr>
        <td align="center">
            {if ($discipline->ordering > 1)}
                <a href="disciplines.php?action=up&id={$discipline->getId()}">
                    <img src="{$web_root}images/{$icon_theme}/16x16/actions/go-up.png" border="0">
                </a>
            {/if}
            {$discipline->ordering}
            {if ($discipline->ordering < $cycle->disciplines->getCount())}
                <a href="disciplines.php?action=down&id={$discipline->getId()}">
                    <img src="{$web_root}images/{$icon_theme}/16x16/actions/go-down.png" border="0">
                </a>
            {/if}
        </td>
        <td>{counter}</td>
        <td><a href="#" onclick="if (confirm('Действительно удалить дисциплину {if !is_null($discipline->discipline)}{$discipline->discipline->getValue()}{/if}')) { location.href='disciplines.php?action=del&id={$discipline->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td>
            {if !is_null($discipline->discipline)}
                <a href="disciplines.php?action=edit&id={$discipline->getId()}">{$discipline->discipline->getValue()}</a>
            {/if}
        </td>
        <td>
            {$discipline->getLaborValue()}
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
    {/foreach}
</table>
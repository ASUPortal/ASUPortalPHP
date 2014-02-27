{function name=print_discipline_row level=0}
    <tr>
        <td>{$cycle->title_abbreviated}</td>
        <td>&nbsp;</td>
        <td>
            {if !is_null($discipline->cycle)}
    			{if ($discipline->cycle->number != "")}
					{$discipline->cycle->number}.
				{/if}
            {/if}
            {if $discipline->parent_id !== "0"}
                {if !is_null($discipline->parent)}
                    {$discipline->parent->ordering}
                {/if}
				{if ($discipline->ordering) != ""}
					.{$discipline->ordering}
				{/if}
            {else}
                {$discipline->ordering}
            {/if}
        </td>
        <td>
            {if !is_null($discipline->discipline)}
                {for $i=1 to $level}
                    &nbsp;&nbsp;
                {/for}
                <a href="disciplines.php?action=edit&id={$discipline->getId()}">{$discipline->discipline->getValue()}</a>
            {/if}
        </td>
        <!-- Распределение по видам занятий -->
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborValue()}
            {/if}
        </td>
        {foreach $labors->getItems() as $key=>$value}
            <td>
                {if !is_null($discipline->getLaborByType($key))}
                    {$discipline->getLaborByType($key)->value}
                {/if}
            </td>
        {/foreach}
        <!-- Распределение по видам занятий -->
        <td>&nbsp;</td>
    </tr>
{/function}
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <td rowspan="2">Цикл</td>
        <td colspan="3">Дисциплины</td>
        <td colspan="{$labors->getCount() + 1}">Распределение нагрузки по видям занятий</td>
        <td>Форма итогового контроля</td>
    </tr>
    <tr>
        <td>Тип</td>
        <td>№</td>
        <td>Наименование дисциплины</td>
        <td>Всего</td>
        {foreach $labors->getItems() as $labor}
            <td>
                {if !is_null($labor->type)}
                    {$labor->type->getValue()}
                {/if}
            </td>
        {/foreach}
        <td>&nbsp;</td>
    </tr>
    </thead>
    {foreach $corriculum->cycles->getItems() as $cycle}
        <tr>
            <td colspan="{(8 + $labors->getCount())}">
                <a href="cycles.php?action=edit&id={$cycle->getId()}">{$cycle->title}</a>
            </td>
        </tr>
        {foreach $cycle->disciplines->getItems() as $discipline}
            {print_discipline_row discipline=$discipline level=0}
            {foreach $discipline->children->getItems() as $child}
                {print_discipline_row discipline=$child level=1}
            {/foreach}
        {/foreach}
    {/foreach}
</table>

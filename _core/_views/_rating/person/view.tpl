{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Показатели преподавателя {$person->getName()} (за {$year->getValue()} год)</h2>
{CHtml::helpForCurrentPage()}


<table width="100%" border="1" cellspacing="0" cellpadding="0">
    <tr>
        <th>Показатель</th>
        <th align="center">Значение</th>
        <th>Всего</th>
        <th>Процент от общего</th>
        <th>Среднее значение</th>
        <th>Максимум</th>
        <th>Минимум</th>
    </tr>
    {foreach $person->getRatingIndexesByYear($year)->getItems() as $index}
    <tr>
        <td><strong>{$index->title}</strong></td>
        <td>&nbsp;</td>
        <td align="center" rowspan="{$index->getIndexValues()->getCount() + 3}">{$indexes->getItem($index->getId())->getTotalValue()}</td>
        <td align="center" rowspan="{$index->getIndexValues()->getCount() + 3}">{if $indexes->getItem($index->getId())->getTotalValue() != 0}
            {round(100 * $index->getIndexValue()/$indexes->getItem($index->getId())->getTotalValue())}%
        {/if}</td>
        <td align="center" rowspan="{$index->getIndexValues()->getCount() + 3}">{$indexes->getItem($index->getId())->getAverageValue()}</td>
        <td align="center" rowspan="{$index->getIndexValues()->getCount() + 3}">{$indexes->getItem($index->getId())->getMaxValue()}</td>
        <td align="center" rowspan="{$index->getIndexValues()->getCount() + 3}">{$indexes->getItem($index->getId())->getMinValue()}</td>
    </tr>
        {foreach $index->getIndexValues()->getItems() as $value}
        <tr>
            <td>{$value->getTitle()}</td>
            <td align="center">{$value->getValue()}</td>
        </tr>
        {/foreach}
    <tr>
        <td align="right"><strong>Итого:</strong></td>
        <td align="center">{$index->getIndexValue()}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    {/foreach}
</table>

{/block}

{block name="asu_right"}
{include file="_rating/person/view.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Показатели</h2>

    {CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), $year->getId(), "", "", "onchange='location.href=\"indexes.php?action=index&year=\" + this.value'")}

<table cellpadding="0" cellspacing="0" border="1" id="dataTable">
    <thead>
    <tr>
        <th>&nbsp;</th>
        <th>#</th>
        <th>Название</th>
        <th>Значений</th>
        <th width="3">&nbsp;</th>
    </tr>
    </thead>
    {foreach $indexes->getItems() as $index}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить показатель {$index->title}')) { location.href='?action=delete&id={$index->id}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td><a href="?action=edit&id={$index->id}">{$index->title}</a></td>
            <td align="center">{$index->getAvailableIndexValues()->getCount()}</td>
            <td><input type="checkbox" name="selected[]" value="{$index->getId()}"></td>
        </tr>
    {/foreach}
</table>

<div id="targetYearDialog" title="Копирование показателя" style="display: none; ">
    <p>Укажите целевой год для копирования:</p>
    <p>{CHtml::dropDownList("target_year", CTaxonomyManager::getYearsList(), CUtils::getCurrentYear()->getId(), "target_year")}</p>
</div>
{/block}

{block name="asu_right"}
{include file="_rating/index/index.right.tpl"}
{/block}
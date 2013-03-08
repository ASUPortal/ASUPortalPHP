{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочники: правка</h2>

Просмотр справочника {CHtml::dropDownList("taxonomy_id", CTaxonomyManager::getTaxonomiesList(), $taxonomy->getId(), "taxonomy_id")}

<table cellpadding="0" cellspacing="0" border="1" width="99%">
    <tr class="text">
        <th align="center"></th>
        <th align="center">№</th>
        <th align="center">Значение</th>
        <th align="center">Псевдоним</th>
    </tr>

    {foreach $taxonomy->getTerms()->getItems() as $item}
    <tr class="text" bgcolor="#DFEFFF">
        <td><a href="?action=delete&id={$item->id}" onclick="if (!confirm('Вы действительно хотите удалить термин {$item->getValue()}?')){ return false }"><img src="{$web_root}images/todelete.png"></a></td>
        <td>{counter}</td>
        <td><a href="?action=editTerm&id={$item->id}">{$item->getValue()}</a></td>
        <td>{$item->getAlias()}</td>
    </tr>
    {/foreach}
</table>
{/block}

{block name="asu_right"}
{include file="_taxonomy/index.right.tpl"}
{/block}
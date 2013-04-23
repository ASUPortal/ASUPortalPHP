{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочники</h2>

<table cellpadding="0" cellspacing="0" border="1" width="99%">
    <tr>
        <th align="center"></th>
        <th align="center">№</th>
        <th align="center">Название</th>
        <th align="center">Терминов в таксономии</th>
        <th>Псевдоним</th>
    </tr>
    {foreach $taxonomies->getItems() as $taxonomy}
        <tr>
            <td><a href="?action=deleteTaxonomy&id={$taxonomy->id}" onclick="if (!confirm('Вы действительно хотите удалить таксономию {$taxonomy->getName()}?')){ return false }"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=index&id={$taxonomy->id}">{$taxonomy->getName()}</a></td>
            <td>{$taxonomy->getTerms()->getCount()}</td>
            <td>{$taxonomy->getAlias()}</td>
        </tr>
    {/foreach}
    {foreach $legacy->getItems() as $taxonomy}
        <tr>
            <td><a href="?action=deleteLegacyTaxonomy&id={$taxonomy->id}" onclick="if (!confirm('Вы действительно хотите удалить таксономию {$taxonomy->getName()}?')){ return false }"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td><a href="?action=legacy&id={$taxonomy->getId()}">{$taxonomy->getName()}</a></td>
            <td> - </td>
            <td>{$taxonomy->getAlias()}</td>
        </tr>
    {/foreach}
</table>
{/block}

{block name="asu_right"}
    {include file="_taxonomy/list.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
<script>
    jQuery(document).ready(function(){
       jQuery("#tabs").tabs();
    });
</script>

    <h2>Справочники: правка</h2>

    Просмотр справочника {CHtml::dropDownList("taxonomy_id", CTaxonomyManager::getLegacyTaxonomiesObjectsList(), $taxonomy->getId(), "taxonomy_id")}

<div id="tabs">
    <ul style="height: 30px; ">
        <li><a href="#tab-list">Термины таксономии</a></li>
        <li><a href="#tab-common">Общая информация</a></li>
    </ul>
    <div id="tab-list">
        <table cellpadding="0" cellspacing="0" border="1" width="99%">
            <tr class="text">
                <th align="center"></th>
                <th align="center">№</th>
                <th align="center">Значение</th>
                <th align="center">Псевдоним</th>
            </tr>

            {foreach $taxonomy->getTerms()->getItems() as $item}
                <tr class="text" bgcolor="#DFEFFF">
                    <td><a class="icon-trash" href="?action=deleteLegacyTerm&id={$item->id}&taxonomy_id={$taxonomy->getId()}" onclick="if (!confirm('Вы действительно хотите удалить термин {$item->getValue()}?')){ return false }"></a></td>
                    <td>{counter}</td>
                    <td><a href="?action=editLegacyTerm&id={$item->id}&taxonomy_id={$taxonomy->getId()}">{$item->getValue()}</a></td>
                    <td>{$item->getAlias()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tab-common">
        {include file="_taxonomy/form.legacy.tpl"}
    </div>
</div>

{/block}

{block name="asu_right"}
    {include file="_taxonomy/legacy.right.tpl"}
{/block}

{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Справочники: правка</h2>

Просмотр справочника {CHtml::dropDownList("taxonomy_id", CTaxonomyManager::getTaxonomiesList(), $taxonomy->getId(), "taxonomy_id")}


<ul class="nav nav-tabs">
    <li class="active"><a href="#tab-list" data-toggle="tab">Термины таксономии</a></li>
    <li><a href="#tab-common" data-toggle="tab">Общая информация</a></li>
</ul>
<div class="tab-content">
    <div id="tab-list" class="tab-pane active">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <tr>
                <th align="center"></th>
                <th align="center">№</th>
                <th align="center">Значение</th>
                <th align="center">Псевдоним</th>
            </tr>

            {foreach $taxonomy->getTerms()->getItems() as $item}
                <tr class="text">
                    <td><a class="icon-trash" href="?action=delete&id={$item->id}" onclick="if (!confirm('Вы действительно хотите удалить термин {$item->getValue()}?')){ return false }"></a></td>
                    <td>{counter}</td>
                    <td><a href="?action=editTerm&id={$item->id}">{$item->getValue()}</a></td>
                    <td>{$item->getAlias()}</td>
                </tr>
            {/foreach}
        </table>
    </div>
    <div id="tab-common" class="tab-pane">
        {include file="_taxonomy/form.Taxonomy.tpl"}
    </div>
</div>
{/block}

{block name="asu_right"}
{include file="_taxonomy/index.right.tpl"}
{/block}
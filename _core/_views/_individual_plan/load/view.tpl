{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальный план</h2>

    {CHtml::helpForCurrentPage()}

    {if $person->getIndPlansByYears(CRequest::getInt("year"))->getCount() == 0}
        Нет информации для отображения
    {else}
        <h3>{$person->getName()}</h3>

        {foreach $person->getIndPlansByYears(CRequest::getInt("year"))->getItems() as $year=>$yearLoad}
            {include file="_individual_plan/load/subform.yearLoad.tpl"}
        {/foreach}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/view.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление вида работы ({if ($object->work_type == "1")}
            {$object->getLoad()->person->getName()}
        {else}
            {$object->load->person->getName()}
        {/if})</h2>

    {CHtml::warningSummary($object)}
    
    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/work//form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/work//add.right.tpl"}
{/block}

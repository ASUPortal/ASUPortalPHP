{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование вида работы ({if ($object->work_type == "1")}
            {$object->getLoad()->person->getName()}
        {else}
            {$object->load->person->getName()}
        {/if})</h2>
        
    {if ($object->isEditRestriction())}
		<div class="alert">Установлено ограничение на редактирование!</div>
	{/if}
    
    {CHtml::helpForCurrentPage()}

    {include file="_individual_plan/work//form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_individual_plan/work//edit.right.tpl"}
{/block}

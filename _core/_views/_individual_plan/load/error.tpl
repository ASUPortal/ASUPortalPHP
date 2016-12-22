{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Копирование индивидуального плана</h2>
    {CHtml::helpForCurrentPage()}
    
    <div class="alert">
		{$message}
    </div>
{/block}

{block name="asu_right"}
    {include file="_individual_plan/load/common.right.tpl"}
{/block}
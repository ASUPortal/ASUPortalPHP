{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование нагрузки</h2>

    {CHtml::helpForCurrentPage()}
    
    {CHtml::warningSummary($studyLoad)}

	{include file="_study_loads/form.tpl"}

{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
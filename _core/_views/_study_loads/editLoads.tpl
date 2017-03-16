{extends file="_core.3col.tpl"}

{block name="asu_center"}

<h2>План годовой нагрузки</h2>
    {CHtml::helpForCurrentPage()}

	{if $loadsFall->getCount() == 0 and $loadsSpring->getCount() == 0}
		Нет объектов для отображения
	{else}
		{if $loadsFall->getCount() != 0}
			<div class="alert alert-info">Осенний семестр</div>
			{$studyLoads = $loadsFall}
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		{if $loadsSpring->getCount() != 0}
			<div class="alert alert-info">Весенний семестр</div>
			{$studyLoads = $loadsSpring}
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
    {/if}

{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
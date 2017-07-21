{extends file="_core.3col.tpl"}

{block name="asu_center"}

<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(270deg);
		-ms-transform: rotate(270deg);
		-o-transform: rotate(180deg);
		
		-webkit-writing-mode: vertical-rl;
		max-width: 30px;
	}
</style>

{function name=clearNullValues level=0}
  {if (floatval(str_replace(',','.',$number)) == 0 or $number == 0)}
     &nbsp;
  {else}
     {$number}
  {/if}
{/function}

{if (!is_null($lecturer))}
<h2>План годовой нагрузки {$lecturer->getName()}</h2>
    {CHtml::helpForCurrentPage()}
	
	{include file="_study_loads/subform.showLoads.tpl"}

	{if $loadsFall->getCount() == 0 and $loadsSpring->getCount() == 0}
		Нет объектов для отображения
	{else}
		<div class="alert alert-info"><b>Всего за год: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes),1,',','') level=0}
						( с учётом филиалов: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerWithFilials($lecturer, $year, $loadTypes),1,',','') level=0})</b></div>
		{if $loadsFall->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">Осенний семестр</div>
			
			{$studyLoads = $loadsFall}
			{$loadsId = "loadsFall"}
			{$part = CStudyLoadYearPartsConstants::FALL}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		{if $loadsSpring->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">Весенний семестр</div>
			
			{$studyLoads = $loadsSpring}
			{$loadsId = "loadsSpring"}
			{$part = CStudyLoadYearPartsConstants::SPRING}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		<div class="alert alert-info"><b>Всего за год: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes),1,',','') level=0}
						( с учётом филиалов: {clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerWithFilials($lecturer, $year, $loadTypes),1,',','') level=0})</b></div>
    {/if}
{else}
    <div class="alert">Не выбран преподаватель!</div>
{/if}
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
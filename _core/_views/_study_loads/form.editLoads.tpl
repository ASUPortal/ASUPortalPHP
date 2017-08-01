{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование нагрузки по {if $isBudget}бюджету{else}контракту{/if}</h2>

{CHtml::helpForCurrentPage()}

<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(270deg);
		-ms-transform: rotate(270deg);
		-o-transform: rotate(180deg);
		
		-webkit-writing-mode: vertical-rl;
		max-width: 30px;
	}
	.input-load {
        width: 40px;
        padding: 1px !important;
        margin: 0px !important;
        border-radius: 0px !important;
    }
    .table-load {

    }
    .table-load td {
        padding: 2px !important;
    }
</style>

{function name=clearNullValues level=0}
  {if (floatval(str_replace(',','.',$number)) == 0 or $number == 0)}
     &nbsp;
  {else}
     {$number}
  {/if}
{/function}

	{include file="_study_loads/subform.showLoads.tpl"}

	{if $loadsFall->getCount() == 0 and $loadsSpring->getCount() == 0}
		Нет объектов для отображения
	{else}
		{if $loadsFall->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">ОСЕННИЙ семестр</div>
			
			{$studyLoads = $loadsFall}
			{$loadsId = "loadsFall"}
			{$part = CStudyLoadYearPartsConstants::FALL}
			
			{include file="_study_loads/subform.editLoads.tpl"}
		{/if}
		{if $loadsSpring->getCount() != 0}
			<div class="alert alert-info" style="text-align:center;">Весенний семестр</div>
			
			{$studyLoads = $loadsSpring}
			{$loadsId = "loadsSpring"}
			{$part = CStudyLoadYearPartsConstants::SPRING}
			
			{include file="_study_loads/subform.editLoads.tpl"}
		{/if}
    {/if}

<script>
	$(document).ready(function() {
		updateTableStripe();
		function updateTableStripe() {
			$('.table[rel="stripe"] tr').each(function(i) {
				if (i % 2 === 0) {
					$(this).find("td").css('background', '#c5d0e6');
				} else {
					$(this).find("td").css('background', '#DFEFFF');
				}
			});
		}
	});
</script>
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
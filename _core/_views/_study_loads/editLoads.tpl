{extends file="_core.3col.tpl"}

{block name="asu_center"}

<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-o-transform: rotate(180deg);
		transform: rotate(180deg);
		
		writing-mode:tb-rl;
		height:350px;
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

    <form action="index.php" method="post">
		{CHtml::hiddenField("action", "showLoadTypes")}
		{CHtml::hiddenField("kadri_id", $lecturer->getId())}
		{CHtml::hiddenField("year_id", $year->getId())}
	    <table border="0" width="50%" class="tableBlank">
			<tr>
				<td valign="top">
					<div class="controls">
						{CHtml::checkBox("base", "1", $base, "base")}
					</div>
					<label for="base">Основная</label>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::checkBox("additional", "1", $additional, "additional")}
					</div>
					<label for="additional">Дополнительная</label>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::checkBox("premium", "1", $premium, "premium")}
					</div>
					<label for="premium">Надбавка</label>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::checkBox("byTime", "1", $byTime, "byTime")}
					</div>
					<label for="byTime">Почасовка</label>
				</td>
			    <td valign="top">
			    	<div class="controls">
						<input name="" type="submit" class="btn" value="ok">
					</div>	
				</td>
			</tr>
		</table>
	</form>

	{if $loadsFall->getCount() == 0 and $loadsSpring->getCount() == 0}
		Нет объектов для отображения
	{else}
		{if $loadsFall->getCount() != 0}
			<div class="alert alert-info">Осенний семестр</div>
			
			{$studyLoads = $loadsFall}
			{$loadsId = "loadsFall"}
			{$part = CStudyLoadYearPartsConstants::FALL}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		{if $loadsSpring->getCount() != 0}
			<div class="alert alert-info">Весенний семестр</div>
			
			{$studyLoads = $loadsSpring}
			{$loadsId = "loadsSpring"}
			{$part = CStudyLoadYearPartsConstants::SPRING}
			
			{include file="_study_loads/subform.loads.tpl"}
		{/if}
		<table class="table table-bordered table-hover table-condensed">
	        <tr>
	            <th></th>
	            {foreach $studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if in_array($kindId, array(0))}
							<th><div class="vert-text">{$value}</div></th>
						{/if}
	                {/foreach}
	            {/foreach}
	            <th style="vertical-align:bottom; text-align:center;">Всего</th>
	        </tr>
			<tr bgcolor="#ff9966">
				<td><b>Итого за два семестра</b></td>
		            {foreach CStudyLoadService::getStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes)->getItems() as $typeId=>$rows}
						{foreach $rows as $kindId=>$value}
							{if !in_array($kindId, array(0))}
								<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
							{/if}
		                {/foreach}
		            {/foreach}
				<td><b>{clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturer($lecturer, $year, $loadTypes),1,',','') level=0}</b></td>
			</tr>
		</table>
    {/if}
{else}
    <div class="alert">Не выбран преподаватель!</div>
{/if}
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
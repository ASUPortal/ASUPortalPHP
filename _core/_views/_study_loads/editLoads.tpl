{extends file="_core.3col.tpl"}

{block name="asu_center"}

<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-o-transform: rotate(180deg);
		transform: rotate(180deg);
		
		-webkit-writing-mode: vertical-rl;
		min-width: 30px;
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
		{CHtml::hiddenField("year_id", CRequest::getInt("year_id"))}
		
	    <table border="0" class="tableBlank">
			<tr>
				<td valign="top">
					<label for="base">{CHtml::checkBox("base", "1", $base, "base")}&nbsp;Основная</label>
				</td>
				<td>&nbsp;</td>
			    <td valign="top">
					<label for="additional">{CHtml::checkBox("additional", "1", $additional, "additional")}&nbsp;Дополнительная</label>
				</td>
				<td>&nbsp;</td>
			    <td valign="top">
					<label for="premium">{CHtml::checkBox("premium", "1", $premium, "premium")}&nbsp;Надбавка</label>
				</td>
				<td>&nbsp;</td>
			    <td valign="top">
					<label for="byTime">{CHtml::checkBox("byTime", "1", $byTime, "byTime")}&nbsp;Почасовка</label>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
			    <td valign="top">
			    	<div class="controls">
						<input name="" type="submit" class="btn" value="ok">
					</div>	
				</td>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="year_id">Учебный год</label>
				            <div class="controls">
				            	{CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), $selectedYear, "year_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				{if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
					<td valign="top">
						<div class="form-horizontal">
					    	<div class="control-group">
					            <label class="control-label" for="kadri_id">ФИО преподавателя</label>
					            <div class="controls">
					            	{CHtml::dropDownList("kadri_id", CStaffManager::getPersonsListWithType("профессорско-преподавательский состав"), $selectedPerson, "kadri_id", "span12")}
					            </div>
					        </div>
					    </div>
					</td>
				{/if}
			</tr>
		</table>
	</form>

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
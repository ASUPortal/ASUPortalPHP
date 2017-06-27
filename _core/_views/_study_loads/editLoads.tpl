{extends file="_core.3col.tpl"}

{block name="asu_center"}

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
    {/if}
{else}
    <div class="alert">Не выбран преподаватель!</div>
{/if}
{/block}

{block name="asu_right"}
	{include file="_study_loads/common.right.tpl"}
{/block}
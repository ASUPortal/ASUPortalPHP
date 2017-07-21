<form action="index.php" method="post">
	{CHtml::hiddenField("action", "showLoadTypes")}
	{CHtml::hiddenField("kadri_id", $lecturer->getId())}
	{CHtml::hiddenField("year_id", CRequest::getInt("year_id"))}
	{CHtml::hiddenField("redirect", CRequest::getString("action"))}
	{CHtml::hiddenField("isBudget", $isBudget)}
	{CHtml::hiddenField("isContract", $isContract)}
	
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
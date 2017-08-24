{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<h2>Добавление (правка) расписания</h2>
	{CHtml::helpForCurrentPage()}
	
	<h3>Выберите преподавателя</h3><br>
	
	<form action="index.php" method="post">
		{CHtml::hiddenField("action", "showSchedules")}
		{CHtml::hiddenField("redirect", "viewLecturers")}
		{CHtml::hiddenField("year", $year->getId())}
		{CHtml::hiddenField("yearPart", $yearPart->getId())}
		{CHtml::hiddenField("name", $selectedUser)}
		
	    <table border="0" class="tableBlank">
			<tr>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="year">Учебный год</label>
				            <div class="controls">
				            	{CHtml::dropDownList("year", CTaxonomyManager::getYearsList(), $year->getId(), "year_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="yearPart">Учебный семестр</label>
				            <div class="controls">
				            	{CHtml::dropDownList("yearPart", CTaxonomyManager::getYearPartsList(), $yearPart->getId(), "year_part_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="name">Преподаватель</label>
				            <div class="controls">
				            	{CHtml::dropDownList("name", $lecturers, $selectedUser, "user_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
			    <td valign="top">
			    	<div class="controls">
						<input name="" type="submit" class="btn" value="ok">
					</div>	
				</td>
			</tr>
		</table>
	</form>
	
	<h3> или группу</h3><br>
	<form action="index.php" method="post">
		{CHtml::hiddenField("action", "showSchedules")}
		{CHtml::hiddenField("redirect", "viewGroups")}
		{CHtml::hiddenField("year", $year->getId())}
		{CHtml::hiddenField("yearPart", $yearPart->getId())}
		{CHtml::hiddenField("name", $selectedGroup)}
		
	    <table border="0" class="tableBlank">
			<tr>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="year">Учебный год</label>
				            <div class="controls">
				            	{CHtml::dropDownList("year", CTaxonomyManager::getYearsList(), $year->getId(), "year_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="yearPart">Учебный семестр</label>
				            <div class="controls">
				            	{CHtml::dropDownList("yearPart", CTaxonomyManager::getYearPartsList(), $yearPart->getId(), "year_part_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td valign="top">
					<div class="form-horizontal">
				    	<div class="control-group">
				            <label class="control-label" for="name">Группа</label>
				            <div class="controls">
				            	{CHtml::dropDownList("name", $groups, $selectedGroup, "group_selector", "span12")}
				            </div>
				        </div>
				    </div>
				</td>
				<td>&nbsp;&nbsp;&nbsp;</td>
			    <td valign="top">
			    	<div class="controls">
						<input name="" type="submit" class="btn" value="ok">
					</div>	
				</td>
			</tr>
		</table>
	</form>
{/block}

{block name="asu_right"}
	{include file="_schedule/common.right.tpl"}
{/block}
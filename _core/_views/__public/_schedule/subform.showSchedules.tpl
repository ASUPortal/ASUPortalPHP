<form action="{$link}" method="post">
	{CHtml::hiddenField("action", "showSchedules")}
	{CHtml::hiddenField("redirect", CRequest::getString("action"))}
	{CHtml::hiddenField("year", $year->getId())}
	{CHtml::hiddenField("yearPart", $yearPart->getId())}
	{if ($name != "all")}
		{if (!is_null($name))}
			{CHtml::hiddenField("name", $name->getId())}
		{/if}
	{/if}
	
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
			{if ($name != "all")}
				{if ($nameInCell == "studentGroup")}
					<td valign="top">
						<div class="form-horizontal">
					    	<div class="control-group">
					            <label class="control-label" for="name">Преподаватель</label>
					            <div class="controls">
					            	{CHtml::dropDownList("name", $lecturers, $selectedName, "user_selector", "span12")}
					            </div>
					        </div>
					    </div>
					</td>
				{elseif ($nameInCell == "lecturer")}
					<td valign="top">
						<div class="form-horizontal">
					    	<div class="control-group">
					            <label class="control-label" for="name">Группа</label>
					            <div class="controls">
					            	{CHtml::dropDownList("name", $groups, $selectedName, "group_selector", "span12")}
					            </div>
					        </div>
					    </div>
					</td>
				{/if}
			{/if}
			<td>&nbsp;&nbsp;&nbsp;</td>
		    <td valign="top">
		    	<div class="controls">
					<input name="" type="submit" class="btn" value="ok">
				</div>	
			</td>
		</tr>
	</table>
</form>
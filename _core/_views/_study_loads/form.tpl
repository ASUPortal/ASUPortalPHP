<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $studyLoad)}

    {CHtml::errorSummary($studyLoad)}

    <div class="control-group">
        {CHtml::activeLabel("person_id", $studyLoad)}
        <div class="controls">
            {CHtml::activeLookup("person_id", $studyLoad, "staff", false, array(), false, $studyLoad->restrictionAttribute())}
            {CHtml::error("person_id", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("year_id", $studyLoad)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $studyLoad, CTaxonomyManager::getYearsList(), "", "", $studyLoad->restrictionAttribute())}
            {CHtml::error("year_id", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("year_part_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeDropDownList("year_part_id", $studyLoad, CTaxonomyManager::getYearPartsList(), "", "", $studyLoad->restrictionAttribute())}
	        {CHtml::error("year_part_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("discipline_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("discipline_id", $studyLoad, "subjects", false, array(), false, $studyLoad->restrictionAttribute())}
	        {CHtml::error("discipline_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("speciality_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("speciality_id", $studyLoad, "specialities", false, array(), false, $studyLoad->restrictionAttribute())}
	        {CHtml::error("speciality_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("level_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("level_id", $studyLoad, "levels", false, array(), false, $studyLoad->restrictionAttribute())}
	        {CHtml::error("level_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("load_type_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("load_type_id", $studyLoad, "hours_kind_type", false, array(), false, $studyLoad->restrictionAttribute())}
	        {CHtml::error("load_type_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("on_filial", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeCheckBox("on_filial", $studyLoad, "", "", $studyLoad->restrictionAttribute())}
	        {CHtml::error("on_filial", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("study_groups", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("study_groups", $studyLoad, "studentgroup", true, array(), false, $studyLoad->restrictionAttribute())}
	        {CHtml::error("study_groups", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
        {CHtml::activeLabel("groups_count", $studyLoad)}
        <div class="controls">
            {CHtml::activeTextField("groups_count", $studyLoad, "", "", $studyLoad->restrictionAttribute())}
            {CHtml::error("groups_count", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("comment", $studyLoad)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $studyLoad, "", "", $studyLoad->restrictionAttribute())}
            {CHtml::error("comment", $studyLoad)}
        </div>
    </div>
    
	{if ($studyLoad->id != "")}
		<table border="0" width="60%" class="tableBlank">
			<tr>
				<th>&nbsp;</th>
				<th align="center">Бюджет</th>
				<th align="center">Коммерция</th>
			</tr>
			<tr>
				<td align="right">
					{CHtml::label("Число студентов", "", "", true)}
				</td>
				<td align="center">
					{CHtml::activeTextField("students_count", $studyLoad, "", "", $studyLoad->restrictionAttribute())}
				</td>
				<td align="center">
					{CHtml::activeTextField("students_contract_count", $studyLoad, "", "", $studyLoad->restrictionAttribute())}
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			{foreach $studyLoad->getStudyLoadTable()->getTable() as $typeId=>$rows}
	            <tr>
					{foreach $rows as $kindId=>$value}
						{if in_array($kindId, array(0))}
							<td align="right">
								{CHtml::label($value, "", "", true)}
							</td>
						{else}
							<td align="center">
		                       {CHtml::textField($studyLoad->getStudyLoadTable()->getFieldName($typeId, $kindId), $value, "", "", $studyLoad->restrictionAttribute())}
		                    </td>
						{/if}
	                {/foreach}
	            </tr>
	        {/foreach}
		</table>
	{/if}

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
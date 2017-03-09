<form action="index.php" method="post" class="form-horizontal">
    {CHtml::hiddenField("action", "save")}
    {CHtml::activeHiddenField("id", $studyLoad)}

    {CHtml::errorSummary($studyLoad)}

    <div class="control-group">
        {CHtml::activeLabel("kadri_id", $studyLoad)}
        <div class="controls">
            {CHtml::activeLookup("kadri_id", $studyLoad, "staff")}
            {CHtml::error("kadri_id", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("year_id", $studyLoad)}
        <div class="controls">
            {CHtml::activeDropDownList("year_id", $studyLoad, CTaxonomyManager::getYearsList())}
            {CHtml::error("year_id", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
	    {CHtml::activeLabel("part_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeDropDownList("part_id", $studyLoad, $parts)}
	        {CHtml::error("part_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("subject_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("subject_id", $studyLoad, "subjects")}
	        {CHtml::error("subject_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("spec_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("spec_id", $studyLoad, "specialities")}
	        {CHtml::error("spec_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("level_id", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("level_id", $studyLoad, "levels")}
	        {CHtml::error("level_id", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("hours_kind_type", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("hours_kind_type", $studyLoad, "hours_kind_type")}
	        {CHtml::error("hours_kind_type", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("on_filial", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeCheckBox("on_filial", $studyLoad)}
	        {CHtml::error("on_filial", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
	    {CHtml::activeLabel("study_groups", $studyLoad)}
	    <div class="controls">
	        {CHtml::activeLookup("study_groups", $studyLoad, "studentgroup", true)}
	        {CHtml::error("study_groups", $studyLoad)}
	    </div>
	</div>
	
	<div class="control-group">
        {CHtml::activeLabel("groups_cnt", $studyLoad)}
        <div class="controls">
            {CHtml::activeTextField("groups_cnt", $studyLoad)}
            {CHtml::error("groups_cnt", $studyLoad)}
        </div>
    </div>
    
    <div class="control-group">
        {CHtml::activeLabel("comment", $studyLoad)}
        <div class="controls">
            {CHtml::activeTextBox("comment", $studyLoad)}
            {CHtml::error("comment", $studyLoad)}
        </div>
    </div>
	
	<table border="0" width="60%" class="tableBlank">
		<tr>
			<th>&nbsp;</th>
			<th align="center">Бюджет</th>
			<th align="center">Коммерция</th>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("stud_cnt", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("stud_cnt", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("stud_cnt_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("lects", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("lects", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("lects_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("practs", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("practs", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("practs_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("labor", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("labor", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("labor_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("rgr", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("rgr", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("rgr_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("ksr", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("ksr", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("ksr_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("recenz", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("recenz", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("recenz_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("consult", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("consult", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("consult_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("test", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("test", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("test_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("exams", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("exams", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("exams_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("study_pract", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("study_pract", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("study_pract_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("work_pract", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("work_pract", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("work_pract_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("kurs_proj", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("kurs_proj", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("kurs_proj_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("consult_dipl", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("consult_dipl", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("consult_dipl_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("gek", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("gek", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("gek_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("aspirants", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("aspirants", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("aspirants_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("aspir_manage", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("aspir_manage", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("aspir_manage_add", $studyLoad)}
			</td>
		</tr>
		<tr>
			<td align="right">
				{CHtml::activeLabel("duty", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("duty", $studyLoad)}
			</td>
			<td align="center">
				{CHtml::activeTextField("duty_add", $studyLoad)}
			</td>
		</tr>
	</table>

    <div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
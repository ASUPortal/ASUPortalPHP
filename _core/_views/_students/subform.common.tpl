<script>
	jQuery(document).ready(function(){
	{if !is_null($student->getId())}
	    jQuery.ajax({
    		url: web_root + "_modules/_students",
    		data: {
   	 			action: "getCorriculumHoursTotal",
    			id: {$student->getId()}
    		}
    	}).done(function(data){
    		jQuery("#hours_total").html(data);
    	});
        jQuery.ajax({
            url: web_root + "_modules/_students",
            data: {
                action: "getCorriculumTimeDifference",
                id: {$student->getId()}
            }
        }).done(function(data){
            jQuery("#hours_difference").html(data);
        });
	{/if}
	});
</script>

<div id="hours_total" style="color: red; font-size: 150px; position: absolute; right: 5px; "></div>
<div id="hours_difference" style="position: absolute; right: 5px;"></div>

<div class="control-group">
    {CHtml::activeLabel("fio", $student)}
    <div class="controls">
        {CHtml::activeTextField("fio", $student)}
        {CHtml::error("fio", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("fio_rp", $student)}
    <div class="controls">
        {CHtml::activeTextField("fio_rp", $student)}
        {CHtml::error("fio_rp", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("gender_id", $student)}
    <div class="controls">
        {CHtml::activeDropDownList("gender_id", $student, CTaxonomyManager::getGendersList())}
        {CHtml::error("gender_id", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("group_id", $student)}
    <div class="controls">
        {CHtml::activeDropDownList("group_id", $student, $groups)}
        {CHtml::error("group_id", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("telephone", $student)}
    <div class="controls">
        {CHtml::activeTextField("telephone", $student)}
        {CHtml::error("telephone", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("bud_contract", $student)}
    <div class="controls">
        {CHtml::activeDropDownList("bud_contract", $student, $forms)}
        {CHtml::error("bud_contract", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("stud_num", $student)}
    <div class="controls">
        {CHtml::activeTextField("stud_num", $student)}
        {CHtml::error("stud_num", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("birth_date", $student)}
    <div class="controls">
        {CHtml::activeTextField("birth_date", $student, "birth_date")}
        {CHtml::error("birth_date", $student)}
    </div>
</div>

<div class="control-group">
    {CHtml::activeLabel("e-mail", $student)}
    <div class="controls">
        {CHtml::activeTextField("e-mail", $student, "e-mail")}
        {CHtml::error("e-mail", $student)}
    </div>
</div>
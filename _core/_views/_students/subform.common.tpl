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

<p>
    {CHtml::activeLabel("fio", $student)}
    {CHtml::activeTextField("fio", $student)}
    {CHtml::error("fio", $student)}
</p>

<p>
    {CHtml::activeLabel("fio_rp", $student)}
    {CHtml::activeTextField("fio_rp", $student)}
    {CHtml::error("fio_rp", $student)}
</p>

<p>
    {CHtml::activeLabel("gender_id", $student)}
    {CHtml::activeDropDownList("gender_id", $student, CTaxonomyManager::getGendersList())}
    {CHtml::error("gender_id", $student)}
</p>

<p>
    {CHtml::activeLabel("group_id", $student)}
    {CHtml::activeDropDownList("group_id", $student, $groups)}
    {CHtml::error("group_id", $student)}
</p>

<p>
    {CHtml::activeLabel("telephone", $student)}
    {CHtml::activeTextField("telephone", $student)}
    {CHtml::error("telephone", $student)}
</p>

<p>
    {CHtml::activeLabel("bud_contract", $student)}
    {CHtml::activeDropDownList("bud_contract", $student, $forms)}
    {CHtml::error("bud_contract", $student)}
</p>

<p>
    {CHtml::activeLabel("stud_num", $student)}
    {CHtml::activeTextField("stud_num", $student)}
    {CHtml::error("stud_num", $student)}
</p>

<p>
    {CHtml::activeLabel("birth_date", $student)}
    {CHtml::activeTextField("birth_date", $student, "birth_date")}
    {CHtml::error("birth_date", $student)}
</p>
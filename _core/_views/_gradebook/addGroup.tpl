{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление группы записей</h2>

<script>
    jQuery(document).ready(function(){
        jQuery("#date_act").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "button",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true
        });
        jQuery("#group_id").change(function(){
            jQuery.getJSON(
                    web_root + "_modules/_json_service",
                    {
                        controller: "staff",
                        action: "getStudentsByGroup",
                        group: jQuery(this).val()
                    },
                    function (students) {
                        jQuery("#student_container").empty();
                        jQuery.each(students, function (key, value) {
                            var item = jQuery(jQuery("#student_template").find("p")[0]).clone().appendTo("#student_container");

                            var label = jQuery(item).find("label")[0];
                            jQuery(label).html(value);
                            jQuery(label).attr("for", jQuery(label).attr("for") + "[" + key + "]");

                            var select = jQuery(item).find("select")[0];
                            jQuery(select).attr("name", jQuery(select).attr("name") + "[" + key + "]");
                            jQuery(select).attr("id", "student_" + key);
                            jQuery(select).change(function() {
                                if (jQuery(this).val() !== 0) {
                                    jQuery(this).css("background", "yellow");
                                } else {
                                    jQuery(this).css("background", "none");
                                }
                            });
                        });
                    }
            );
        });
    });
</script>

<form action="index.php" method="post">
    {CHtml::hiddenField("action", "saveGroup")}

    <p>{CHtml::errorSummary($activity)}</p>

    <p>
        {CHtml::activeLabel("date_act", $activity)}
        {CHtml::activeTextField("date_act", $activity, "date_act")}
        {CHtml::error("date_act", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("subject_id", $activity)}
        {CHtml::activeDropDownList("subject_id", $activity, CTaxonomyManager::getDisciplinesList(), "subject_id")}
        {CHtml::error("subject_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("kadri_id", $activity)}
        {CHtml::activeDropDownList("kadri_id", $activity, CStaffManager::getPersonsList(), "kadri_id")}
        {CHtml::personTypeFilter("kadri_id", $activity)}
        {CHtml::error("kadri_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("group_id", $activity)}
        {CHtml::activeDropDownList("group_id", $activity, $groups, "group_id")}
        {CHtml::error("group_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("study_act_id", $activity)}
        {CHtml::activeDropDownList("study_act_id", $activity, CTaxonomyManager::getControlTypesList(), "study_act_id")}
        {CHtml::error("study_act_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("study_act_comment", $activity)}
        {CHtml::activeTextField("study_act_comment", $activity, "study_act_comment")}
        {CHtml::error("study_act_comment", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $activity)}
        {CHtml::activeTextField("comment", $activity, "comment")}
        {CHtml::error("comment", $activity)}
    </p>

    <div id="student_template" style="display: none; ">
        <p>
            {CHtml::activeLabel("student", $activity)}
            {CHtml::activeDropDownList("student", $activity, CTaxonomyManager::getMarksList())}
        </p>
    </div>
    <div id="student_container"></div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>

    <p>
        {CHtml::label("Сохранить значения", "saveValues")}
        {CHtml::checkBox("saveValues", "1", true)}
    </p>
</form>
{/block}

{block name="asu_right"}
{include file="_gradebook/addGroup.right.tpl"}
{/block}
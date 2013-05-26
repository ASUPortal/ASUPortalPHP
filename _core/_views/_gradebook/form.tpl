<script>
    require(["dojo/request/xhr"]);
    dojo.ready(function(){
        dojo.connect(dijit.byId("group_id"), "onChange", function(){
            var url = web_root + "_modules/_json_service";
            var xhrParams = {
                handleAs: "json",
                data: {
                    controller: "staff",
                    action: "getStudentsByGroup",
                    group: this.value
                },
                preventCache: true,
                method: "POST"
            };
            //dojo.xhr(url, xhrParams);
            dojo.xhr.post(url, {
                handleAs: "json",
                preventCache: true,
                method: "POST"
            });
        });
    });
    jQuery(document).ready(function(){
        jQuery("#group_id").change(function(){
            jQuery.getJSON(
                web_root + "_modules/_json_service",
                {
                    controller: "staff",
                    action: "getStudentsByGroup",
                    group: jQuery(this).val()
                },
                function (students) {
                    jQuery("#student_id").empty();
                    jQuery.each(students, function (key, value) {
                        jQuery("#student_id").append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            );
        });
        jQuery("#study_mark").change(function() {
            if (jQuery(this).val() !== 0) {
                jQuery(this).css("background", "yellow");
            } else {
                jQuery(this).css("background", "none");
            }
        });
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $activity)}

    <p>{CHtml::errorSummary($activity)}</p>

    <p>
        {CHtml::activeLabel("date_act", $activity)}
        {CHtml::activeDateField("date_act", $activity, "date_act")}
        {CHtml::error("date_act", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("subject_id", $activity)}
        {CHtml::activeDropDownList("subject_id", $activity, CTaxonomyManager::getDisciplinesList(), "subject_id")}
        {CHtml::error("subject_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("kadri_id", $activity)}
        {CHtml::activeDropDownList("kadri_id", $activity, CStaffManager::getPersonsList(), kadri_id)}
        {CHtml::personTypeFilter("kadri_id", $activity)}
        {CHtml::error("kadri_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("group_id", $activity)}
        {CHtml::activeDropDownList("group_id", $activity, $groups, "group_id")}
        {CHtml::error("group_id", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("student_id", $activity)}
        {CHtml::activeDropDownList("student_id", $activity, $students, "student_id")}
        {CHtml::error("student_id", $activity)}
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
        {CHtml::activeLabel("study_mark", $activity)}
        {CHtml::activeDropDownList("study_mark", $activity, CTaxonomyManager::getMarksList(), "study_mark")}
        {CHtml::error("study_mark", $activity)}
    </p>

    <p>
        {CHtml::activeLabel("comment", $activity)}
        {CHtml::activeTextField("comment", $activity, "comment")}
        {CHtml::error("comment", $activity)}
    </p>

    <p>
        {CHtml::submit("Сохранить")}
    </p>

    <p>
        {CHtml::label("Сохранить значения", "saveValues")}
        {CHtml::checkBox("saveValues", "1", true)}
    </p>
</form>
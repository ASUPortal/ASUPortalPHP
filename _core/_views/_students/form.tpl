<script>
    jQuery(document).ready(function(){
        jQuery("#year_school_end").datepicker({
            dateFormat: "yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
        jQuery("#year_university_start").datepicker({
            dateFormat: "yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
        jQuery("#year_university_end").datepicker({
            dateFormat: "yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
        jQuery("#attach_regdate").datepicker({
            dateFormat: "dd.mm.yy",
            showOn: "both",
            buttonImage: "{$web_root}css/_core/jUI/images/calendar.gif",
            buttonImageOnly: true,
            changeYear: true
        });
        jQuery("#tabs").tabs();
        jQuery("#tabs-secondary").tabs();
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $student)}

    <p>{CHtml::errorSummary($student)}</p>

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#tab-common">Общая информация</a></li>
            <li><a href="#tab-basic-education">Начальное образование</a></li>
            <li><a href="#tab-secondary-education">Высшее образование</a></li>
            <li><a href="#tab-work">Работа</a></li>
        </ul>
        <div id="tab-common">
            <p>
                {CHtml::activeLabel("fio", $student)}
                {CHtml::activeTextField("fio", $student)}
                {CHtml::error("fio", $student)}
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
        </div>
        <div id="tab-basic-education">
            <p>
                {CHtml::activeLabel("year_school_end", $student)}
                {CHtml::activeTextField("year_school_end", $student, "year_school_end")}
                {CHtml::error("year_school_end", $student)}
            </p>

            <br>

            <p>
                {CHtml::activeLabel("primary_education_type_id", $student)}
                {CHtml::activeDropDownList("primary_education_type_id", $student, CTaxonomyManager::getTaxonomy("primary_education")->getTermsList())}
                {CHtml::error("primary_education_type_id", $student)}
            </p>
        </div>
        <div id="tab-secondary-education">
            <div id="tabs-secondary">
                <ul style="height: 30px;">
                    <li><a href="#education">Обучение</a></li>
                    <li><a href="#practice">Практика и междис</a></li>
                    <li><a href="#diploma">Вкладыш к диплому</a></li>
                </ul>
                <div id="education">
                    <p>
                        {CHtml::activeLabel("year_university_start", $student)}
                        {CHtml::activeTextField("year_university_start", $student, "year_university_start")}
                        {CHtml::error("year_university_start", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("year_university_end", $student)}
                        {CHtml::activeTextField("year_university_end", $student, "year_university_end")}
                        {CHtml::error("year_university_end", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("education_form_start", $student)}
                        {CHtml::activeDropDownList("education_form_start", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_start", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("education_form_end", $student)}
                        {CHtml::activeDropDownList("education_form_end", $student, CTaxonomyManager::getCacheEducationForms()->getItems())}
                        {CHtml::error("education_form_end", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("education_specialization_id", $student)}
                        {CHtml::activeDropDownList("education_specialization_id", $student, CTaxonomyManager::getTaxonomy("education_specializations")->getTermsList())}
                        {CHtml::error("education_specialization_id", $student)}
                    </p>
                </div>
                <div id="practice">
                    <p>
                        {CHtml::activeLabel("practice_internship_mark_id", $student)}
                        {CHtml::activeDropDownList("practice_internship_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("practice_internship_mark_id", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("practice_undergraduate_mark_id", $student)}
                        {CHtml::activeDropDownList("practice_undergraduate_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("practice_undergraduate_mark_id", $student)}
                    </p>

                    <p>
                        {CHtml::activeLabel("exam_complex_mark_id", $student)}
                        {CHtml::activeDropDownList("exam_complex_mark_id", $student, CTaxonomyManager::getMarksList())}
                        {CHtml::error("exam_complex_mark_id", $student)}
                    </p>
                </div>
                <div id="diploma">

                </div>
            </div>
        </div>
        <div id="tab-work">
            <p>
                {CHtml::activeLabel("work_current", $student)}
                {CHtml::activeTextBox("work_current", $student)}
                {CHtml::error("work_current", $student)}
            </p>

            <p>
                {CHtml::activeLabel("work_proposed", $student)}
                {CHtml::activeTextBox("work_proposed", $student)}
                {CHtml::error("work_proposed", $student)}
            </p>

            <p>
                {CHtml::activeLabel("comment", $student)}
                {CHtml::activeTextBox("comment", $student)}
                {CHtml::error("comment", $student)}
            </p>
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
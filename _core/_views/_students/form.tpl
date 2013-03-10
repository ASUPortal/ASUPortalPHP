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
        jQuery("#birth_date").datepicker({
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
            {include file="_students/subform.common.tpl"}
        </div>
        <div id="tab-basic-education">
            {include file="_students/subform.basic_education.tpl"}
        </div>
        <div id="tab-secondary-education">
            {include file="_students/subform.secondary_education.tpl"}
        </div>
        <div id="tab-work">
            {include file="_students/subform.work.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
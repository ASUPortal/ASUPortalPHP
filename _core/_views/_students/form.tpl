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
        $('#tabs a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
        $('#tabs a:first').tab('show');
    });
</script>

<form action="index.php" method="post" class="form-horizontal">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $student)}

    <p>{CHtml::errorSummary($student)}</p>

    <ul class="nav nav-tabs" id="tabs">
        <li><a href="#tab-common">Общая информация</a></li>
        <li><a href="#tab-basic-education">Начальное образование</a></li>
        <li><a href="#tab-secondary-education">Высшее образование</a></li>
        <li><a href="#tab-work">Работа</a></li>
        <li><a href="#tab-history">История смены групп</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_students/subform.common.tpl"}
        </div>
        <div class="tab-pane" id="tab-basic-education">
            {include file="_students/subform.basic_education.tpl"}
        </div>
        <div class="tab-pane" id="tab-secondary-education">
            {include file="_students/subform.secondary_education.education.tpl"}
        </div>
        <div class="tab-pane" id="tab-work">
            {include file="_students/subform.work.tpl"}
        </div>
        <div class="tab-pane" id="tab-history">
            {include file="_students/subform.history.tpl"}
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
        {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
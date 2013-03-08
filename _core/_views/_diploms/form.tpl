<script>
    jQuery(document).ready(function(){
        jQuery("#tabs").tabs();
    });
</script>

<form action="index.php" method="post">
{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $diplom)}

    <p>{CHtml::errorSummary($diplom)}</p>

    <div id="tabs">
        <ul style="height: 30px; ">
            <li><a href="#common">Тема диплома</a></li>
            <li><a href="#review">Предзащита</a></li>
            <li><a href="#graduate">Защита</a></li>
            <li><a href="#attach">Вкладыш</a></li>
        </ul>
        <div id="common">
            {include file="_diploms/subform.common.tpl"}
        </div>
        <div id="review">
            {include file="_diploms/subform.review.tpl"}
        </div>
        <div id="graduate">
            {include file="_diploms/subform.graduate.tpl"}
        </div>
        <div id="attach">
            {include file="_diploms/subform.attach.tpl"}
        </div>
    </div>

    <p>
        {CHtml::submit("Сохранить")}
    </p>
</form>
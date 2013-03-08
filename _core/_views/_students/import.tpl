{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Импорт студентов</h2>

    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" enctype="multipart/form-data">
        {CHtml::hiddenField("action", "importProcess")}

        <p>{CHtml::errorSummary($form)}</p>

        <p>
            {CHtml::activeLabel("file", $form)}
            {CHtml::activeUpload("file", $form)}
            {CHtml::error("file", $form)}
        </p>

        <p>
            {CHtml::submit("Импортировать")}
        </p>
    </form>
{/block}

{block name="asu_right"}
{include file="_students/import.right.tpl"}
{/block}
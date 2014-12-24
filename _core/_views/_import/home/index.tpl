{extends file="_core.3col.tpl"}

{block name="asu_center"}

<h2>Импорт данных</h2>
    {CHtml::helpForCurrentPage()}

    <h3>Провайдеры импорта:</h3>
    <ul>
        <li>
            <a href="index.php?action=form&provider=CImportMarksFromCSV">Импорт оценок из CSV</a>
        </li>
    </ul>
{/block}

{block name="asu_right"}
{include file="_import/common.right.tpl"}
{/block}

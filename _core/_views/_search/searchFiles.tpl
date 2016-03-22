{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Поиск по файлам</h2>

    {CHtml::helpForCurrentPage()}
    
    <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    	{CHtml::hiddenField("action", "searchByFiles")}
    	{CHtml::textField("stringSearch", "", "", "span12", "placeholder='Введите фразу для поиска'")}
    </form>

{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Обновление индекса файлов</h2>

    {CHtml::helpForCurrentPage()}
    
    <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    	{CHtml::hiddenField("action", "updateIndexFiles")}
    	{CHtml::textField("path", "", "", "span12", "placeholder='Укажите путь к папке с файлами'")}
    </form>

{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}
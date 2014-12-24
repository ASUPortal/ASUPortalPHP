{extends file="_core.3col.tpl"}

{block name="asu_center"}

    <h2>Импорт данных</h2>
    {CHtml::helpForCurrentPage()}

    <form action="index.php" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="action" value="import">
        <input type="hidden" name="provider" value="{$provider}">
        {include file=$formView}
        <div class="control-group">
            <div class="controls">
                {CHtml::submit("Импортировать", false)}
            </div>
        </div>
    </form>
{/block}

{block name="asu_right"}
    {include file="_import/common.right.tpl"}
{/block}

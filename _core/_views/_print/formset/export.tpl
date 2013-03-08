{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Экспорт наборов форм</h2>

    <textarea style="height: 500px; width: 100%;">{$data}</textarea>
{/block}

{block name="asu_right"}
{include file="_print/formset/export.right.tpl"}
{/block}
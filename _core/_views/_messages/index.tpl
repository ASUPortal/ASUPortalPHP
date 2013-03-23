{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Мои сообщения</h2>


    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_messages/index.right.tpl"}
{/block}
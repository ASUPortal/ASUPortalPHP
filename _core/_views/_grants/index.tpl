{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Гранты</h2>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_grants/index.right.tpl"}
{/block}
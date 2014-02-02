{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Система информационного поиска</h2>

    <h3>Schema.xml</h3>
    <textarea style="width: 100%; height: 300px; ">{implode($config, "\n")}</textarea>
{/block}

{block name="asu_right"}
    {include file="_search/common.right.tpl"}
{/block}
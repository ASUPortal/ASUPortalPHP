{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Система информационного поиска</h2>

    <h3>Schema.xml</h3>
    <textarea style="width: 100%; height: 300px; ">{implode($config, "\n")}</textarea>

    <h3>Обновление индекса</h3>
    <div class="progress progress-striped active">
        <div class="bar" style="width: 0%;" id="updateIndexProgress"></div>
    </div>
{/block}

{block name="asu_right"}
    {include file="_search/index.right.tpl"}
{/block}
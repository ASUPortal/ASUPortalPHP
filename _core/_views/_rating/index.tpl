{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Рейтинг преподавателей</h2>

<form>
    <p>
        {CHtml::label("Год", "year")}
        <ul id="years_list" style="border: 1px solid #c0c0c0; "></ul>
    </p>

    <p>
        {CHtml::label("Преподаватель", "person")}
        <ul id="person_list" style="border: 1px solid #c0c0c0; "></ul>
    </p>

    <p>
        {CHtml::label("Показатель", "index")}
        <ul id="index_list" style="border: 1px solid #c0c0c0;"></ul>
    </p>
</form>

<div id="needUpdate" style="display: none;
    border: 2px solid red;
    padding: 10px;
    margin: 10px;
    text-align: center;
    font-weight: bold;">Обновите диаграмму</div>

{CHtml::helpForCurrentPage()}

<div id="graphContainer"></div>

{/block}

{block name="asu_right"}
{include file="_rating/common.right.tpl"}
{/block}
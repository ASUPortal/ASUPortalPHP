{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление распределение по семестрам {$hour->discipline->discipline->name}</h2>

    {include file="_corriculum/_plan/form.hour.tpl"}
{/block}

{block name="asu_right"}
{include file="_corriculum/_plan/addHour.right.tpl"}
{/block}
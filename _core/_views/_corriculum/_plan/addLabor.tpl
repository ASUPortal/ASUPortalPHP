{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление нагрузки в дисциплину {$discipline->discipline->name}</h2>

    {include file="_corriculum/_plan/form.labor.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/addLabor.right.tpl"}
{/block}
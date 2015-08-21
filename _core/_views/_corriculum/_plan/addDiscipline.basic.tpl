{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Добавление дисциплины в обязательную часть</h2>
{CHtml::helpForCurrentPage()}
    {include file="_corriculum/_plan/form.discipline.basic.tpl"}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_plan/addDiscipline.right.tpl"}
{/block}
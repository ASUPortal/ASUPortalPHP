{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Индивидуальный план</h2>
    {CHtml::helpForCurrentPage()}

    <p>Смотрим направо, там кнопки. Здесь должна быть большая справка HowTo</p>
{/block}

{block name="asu_right"}
    {include file="_individual_plan/index.right.tpl"}
{/block}
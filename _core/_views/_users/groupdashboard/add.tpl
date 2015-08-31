{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление элемента рабочего стола</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_users/groupdashboard/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_users/groupdashboard/common.right.tpl"}
{/block}
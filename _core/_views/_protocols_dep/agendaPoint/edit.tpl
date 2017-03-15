{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Редактирование решения</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_protocols_dep/agendaPoint/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_dep/agendaPoint/common.right.tpl"}
{/block}
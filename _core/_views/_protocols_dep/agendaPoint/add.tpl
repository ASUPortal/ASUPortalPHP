{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h3>Добавление решения</h3>

    {CHtml::helpForCurrentPage()}

    {include file="_protocols_dep/agendaPoint/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_dep/agendaPoint/common.right.tpl"}
{/block}
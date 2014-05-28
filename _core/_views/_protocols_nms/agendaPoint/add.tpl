{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление решения</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_protocols_nms/agendaPoint/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_nms/agendaPoint/common.right.tpl"}
{/block}
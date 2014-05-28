{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование протокол НМС</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_protocols_nms/protocol/form.tpl"}
    {include file="_protocols_nms/protocol/subform.points.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_nms/protocol/common.right.tpl"}
{/block}
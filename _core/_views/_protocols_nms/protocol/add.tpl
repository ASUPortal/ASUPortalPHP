{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление протокол НМС</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_protocols_nms/protocol/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_protocols_nms/protocol/common.right.tpl"}
{/block}
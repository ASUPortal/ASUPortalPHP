{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление комиссии</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_state_attestation/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_state_attestation/add.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Редактирование комиссии</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_state_attestation/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="_state_attestation/edit.right.tpl"}
{/block}
{extends file="_core.component.tpl"}

{block name="asu_center"}
<h2>Редактирование члена комиссии</h2>

    {CHtml::helpForCurrentPage()}

{include file="_state_attestation/members/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_state_attestation/members/common.right.tpl"}
{/block}
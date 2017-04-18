{extends file="_core.component.tpl"}

{block name="asu_center"}
    <h2>Добавление члена комиссии</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_diploms/diplom_preview_members/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_preview_members/common.right.tpl"}
{/block}

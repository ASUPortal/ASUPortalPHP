{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление проверки ВКР на антиплагиат</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_diploms/diplom_check/form.tpl"}
{/block}

{block name="asu_right"}
	{include file="_diploms/diplom_check/add.right.tpl"}
{/block}

{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Мастер создания билетов. Шаг 2 из 2</h2>
{CHtml::helpForCurrentPage()}

    {include file="_state_exam/_tickets/form.wizard.step2.tpl"}
{/block}

{block name="asu_right"}
{include file="_state_exam/_tickets/add.right.tpl"}
{/block}
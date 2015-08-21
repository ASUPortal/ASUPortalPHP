{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Добавление вопроса</h2>
    {if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}

    {include file="__public/_question_add/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="__public/_question_add/index.right.tpl"}
{/block}

{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$page->title}</h2>
    {if (CSession::isAuth())}
        {CHtml::helpForCurrentPage()}
    {/if}

    {$page->page_content}
{/block}

{block name="asu_right"}
    {if (CSession::isAuth())}
        {include file="_public.actions.tpl"}
    {/if}
{/block}
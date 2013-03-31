{extends file="_public.main.tpl"}

{block name="asu_center"}
    <h2>Учебные материалы</h2>

    {if ($folders->getCount() == 0)}
        Нет учебных материалов.
    {else}
        {include file="_library/public.subform.search.tpl"}
        {include file="_library/public.subform.latest.tpl"}
        {include file="_library/public.subform.alphabet.tpl"}
        {include file="_library/public.subform.list.tpl"}
    {/if}
{/block}

{block name="asu_right"}
    {if (CSession::isAuth())}
        {include file="_public.actions.tpl"}
    {/if}
{/block}
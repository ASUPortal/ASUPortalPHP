{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Зарегистрированные ресурсы</h2>
    {CHtml::helpForCurrentPage()}

    {if is_null($resources)}
        Нет зарегистрированных ресурсов
    {else}
        <ul>
        {foreach $resources as $i}
            <li><a href="?action=view&id={$i->getId()}">{$i->getName()}</a></li>
        {/foreach}
        </ul>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_resources/index.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Зарегистрированные меню</h2>

    {CHtml::helpForCurrentPage()}

    <ul>
    {foreach $menus as $menu}
        <li><a href="?action=view&id={$menu->getId()}">{$menu->getName()}</a></li>
    {/foreach}
    </ul>
{/block}

{block name="asu_right"}
    {include file="_menumanager/common.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
    {function name=menuItemsAsList level=0}
        {foreach $data as $entry}
            {if ($entry->getChilds()->getCount() > 0)}
            <tr>
                <td>
                    {section name=tab loop=$level}&nbsp;&nbsp;{/section}
                    <a href="?action=viewItem&id={$entry->getId()}">{$entry->getName()}</a>
                </td>
                <td>{$entry->anchor}</td>
                <td>
                    {if $entry->published == 1}Да{else}Нет{/if}
                </td>
                <td>
                    <ul>
                    {foreach $entry->roles->getItems() as $role}
                        <li>{$role->getName()}</li>
                    {/foreach}
                    </ul>
                </td>
            </tr>
                {call name=menuItemsAsList data=$entry->getChilds()->getItems() level=$level+1}
            {else}
            <tr>
                <td>
                    {section name=tab loop=$level}&nbsp;&nbsp;{/section}
                    <a href="?action=viewItem&id={$entry->getId()}">{$entry->getName()}</a>
                </td>
                <td>{$entry->anchor}</td>
                <td>
                    {if $entry->published == 1}Да{else}Нет{/if}
                </td>
                <td>
                    <ul>
                        {foreach $entry->roles->getItems() as $role}
                            <li>{$role->getName()}</li>
                        {/foreach}
                    </ul>
                </td>
            </tr>
            {/if}
        {/foreach}
    {/function}

<h2>Меню {$menu->getName()}</h2>

    {CHtml::helpForCurrentPage()}

    Пункты меню
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>Пункт меню</th>
            <th>Ссылка</th>
            <th>Опубликован</th>
            <th>Доступен для пользователей с ролью</th>
        </tr>
        {call name=menuItemsAsList data=$menu->getMenuItemsInHierarchy()->getItems()}
    </table>
{/block}

{block name="asu_right"}
    {include file="_menumanager/view.right.tpl"}
{/block}
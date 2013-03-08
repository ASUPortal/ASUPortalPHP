{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Пункт меню {$item->getName()}</h2>

    {CHtml::helpForCurrentPage()}

    <p>
        <b>Ссылка:</b> {$item->getLink()}
    </p>

    <p>
        Доступно для пользователей, обладающих ролями:

        <ul>
        {foreach $item->roles->getItems() as $role}
            <li>{$role->getName()}</li>
        {/foreach}
        </ul>
    </p>
{/block}

{block name="asu_right"}
    {include file="_menumanager/viewItem.right.tpl"}
{/block}
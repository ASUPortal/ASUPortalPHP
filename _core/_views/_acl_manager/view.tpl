{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Пользователь {$user->getName()} ({$user->getLogin()})</h2>

    <strong>Пользователь обладает ролями:</strong>
    <ul>
        {foreach $user->getRoles()->getItems() as $role}
        <li>{$role->getName()}</li>
        {/foreach}
    </ul>
{/block}

{block name="asu_right"}
    {include file="_acl_manager/view.right.tpl"}
{/block}
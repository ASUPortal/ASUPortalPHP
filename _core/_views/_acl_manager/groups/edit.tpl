{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование группы пользователей</h2>

{include file="_acl_manager/groups/form.tpl"}
{/block}

{block name="asu_right"}
{include file="_acl_manager/groups/edit.right.tpl"}
{/block}
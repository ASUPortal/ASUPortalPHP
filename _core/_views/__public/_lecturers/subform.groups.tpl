{if ($lect->getGroups()->getCount() == 0)}
	записей на портале нет
{else}
<ul>
{foreach $lect->getGroups()->getItems() as $group}
	<li><a href="{$web_root}p_stgroups.php?onget=1&group_id={$group->id}">{$group->name}</a></li>
{/foreach}
</ul>
{/if}

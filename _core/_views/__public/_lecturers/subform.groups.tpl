{if ($lect->getSupervisedGroups()->getCount() == 0)}
	записей на портале нет
{else}
<ul>
{foreach $lect->getSupervisedGroups()->getItems() as $group}
	<li><a href="{$web_root}p_stgroups.php?onget=1&group_id={$group->id}">{$group->name}</a></li>
{/foreach}
</ul>
{/if}

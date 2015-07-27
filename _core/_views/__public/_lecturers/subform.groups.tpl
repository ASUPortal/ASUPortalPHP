{if ($lect->getSupervisedGroups()->getCount() == 0)}
	записей на портале нет
{else}
<ul>
{foreach $lect->getSupervisedGroups()->getItems() as $group}
	<li><a href="{$web_root}_modules/_student_groups/public.php?action=view&id={$group->id}">{$group->name}</a></li>
{/foreach}
</ul>
{/if}

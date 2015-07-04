{if ($lect->getSubjects()->getCount() == 0)}
	пособий на портале нет
{else}
<ul>
{foreach $lect->getSubjects()->getItems() as $subject}
	<li><a href="{$web_root}p_library.php?onget=1&getdir={$subject->nameFolder}">{$subject->name} ({$subject->f_cnt})</a></li>
{/foreach}
</ul>
{/if}
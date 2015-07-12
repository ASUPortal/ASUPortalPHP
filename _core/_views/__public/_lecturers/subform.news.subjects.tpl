{if ($lect->getManuals()->getCount() == 0)}
	пособий на портале нет
{else}
<ul>
{foreach $lect->getManuals()->getItems() as $manual}
	<li><a href="{$web_root}p_library.php?onget=1&getdir={$manual->nameFolder}">{$manual->name} ({$manual->f_cnt})</a></li>
{/foreach}
</ul>
{/if}
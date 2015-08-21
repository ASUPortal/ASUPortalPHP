{if ($lect->getManuals()->getCount() == 0)}
	пособий на портале нет
{else}
<ul>
{foreach $lect->getManuals()->getItems() as $manual}
	<li><a href="{$web_root}_modules/_library/index.php?action=publicView&id={$manual->nameFolder}">{$manual->name} ({$manual->f_cnt})</a></li>
{/foreach}
</ul>
{/if}
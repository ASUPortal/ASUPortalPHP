{if ($lect->getPage()->getCount() == 0)}
	страниц на портале нет
{else}
<ul>
{foreach $lect->getPage()->getItems() as $page}
	<li><a href="{$web_root}_modules/_pages/index.php?action=view&id={$page->id}">{$page->title}</a></li>
{/foreach}
</ul>
{/if}
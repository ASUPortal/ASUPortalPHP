{if ($lect->getInfoPages()->getCount() == 0)}
	Информации о сотруднике нет
{else}
	{foreach $lect->getInfoPages()->getItems() as $page}
		{$page->page_content}<br><br>
	{/foreach}
{/if}
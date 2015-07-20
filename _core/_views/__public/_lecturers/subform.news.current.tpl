{if ($lect->getNewsCurrentYear()->getCount() == 0)}
	объявлений на портале нет
{else}
<ul>
{foreach $lect->getNewsCurrentYear()->getItems() as $new}
	<div id="news{$new->id}" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel">{$new->title}</h3>
		</div>
		<div class="modal-body">
			{if ({$new->image}!='')}
				<img src="{$web_root}images/news/{$new->image}">
			{/if}
			{CUtils::getReplacedMessage({$new->file})}
			{if ({$new->file_attach}!='')}
				<br><div>Прикреплен файл: <a href="{$web_root}news/attachement/{$new->file_attach}">
				<img src="{$web_root}images/design/attachment.gif" border=0><b>{$new->file_attach}</b></a></div>
			{/if}
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</div>
</div>
	<li><a href="#news{$new->id}" data-toggle="modal">{$new->title} от {$new->date_time|date_format:"d.m.Y"}</a></li>
{/foreach}
</ul>
{/if}
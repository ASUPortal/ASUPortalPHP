{if ($lect->getNewsOld()->getCount() == 0)}
	объявлений на портале нет
{else}
<ul>
{foreach $lect->getNewsOld()->getItems() as $newOld}
	<div id="news_old{$newOld->id}" class="modal hide fade">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="myModalLabel">{$newOld->title}</h3>
		</div>
		<div class="modal-body">
			{if ({$newOld->image}!='')}
				<img src="{$web_root}images/news/{$newOld->image}">
			{/if}
			{CUtils::getReplacedMessage({$newOld->file})}
			{if ({$newOld->file_attach}!='')}
				<br><div>Прикреплен файл: <a href="{$web_root}news/attachement/{$newOld->file_attach}">
				<img src="{$web_root}images/design/attachment.gif" border=0><b>{$newOld->file_attach}</b></a></div>
			{/if}
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
		</div>
	</div>
	<li><a href="#news_old{$newOld->id}" data-toggle="modal">{$newOld->title} от {$newOld->date_time|date_format:"d.m.Y"}</a></li>
{/foreach}
</ul>
{/if}

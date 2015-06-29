{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2 style="text-align:center">{$lect->FIO}</h2>
    
    <script>
		function hide_show(id_name,mode,debug) { 
		    var elem=document.getElementById(id_name);
		    if (elem!=null) {
		       if (mode!=null)
			   switch (mode) {
			   case 'show':
			      elem.style.display='';
			      break;
			   case 'hide':
			      elem.style.display='none';
			      break;
			   case '':
			       if (elem.style.display=='')  elem.style.display='none';
			       else elem.style.display=''; 
			       break;
			   default:

			    }
		       else {
			       if (elem.style.display=='')  elem.style.display='none';
			       else elem.style.display='';
		       }
			}
		    else { if (debug!=null && debug) alert('элемент не найден');}   
		}
	</script>

    	<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
    	<tr>
    	<td>
    		<div class="text">
    		{if ($biogs->getCount() == 0)}
        		Биография не выложена
    		{else}
    			{$pathPhoto}
    			{CHtml::biographyView()}
    		{/if}
    		</div><br>
	
	    	<div class=text style="font-weight:bold; text-decoration:underline;">Веб-страницы на портале: ({$pages->getCount()})</div>
	    	{if ($pages->getCount() == 0)}
	    		<div class=text>&nbsp;- веб-страниц на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $pages->getItems() as $page}
	    		<li><a href="{$web_root}_modules/_pages/index.php?action=view&id={$page->id}">{$page->title}</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;">Список пособий на портале: ({$subjects->getCount()})</div>
	    	{if ($subjects->getCount() == 0)}
	    		<div class=text>&nbsp;- пособий на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $subjects->getItems() as $subject}
	    		<li><a href="{$web_root}p_library.php?onget=1&getdir={$subject->nameFolder}">{$subject->name} ({$subject->f_cnt})</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"> Объявления текущего учебного года: ({$news->getCount()})</div>
	    	{if ($news->getCount() == 0)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $news->getItems() as $new}
	    		<div id="news{$new->id}" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">{$new->title}</h3>
					</div>
					<div class="modal-body">
						{if ({$new->image}!='')}
							<img src="{$web_root}images/news/{$new->image}">
						{/if}
						{CUtils::msg_replace({$new->file})}
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
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('news_old'); return false;">Объявления прошлых учебных лет: ({$newsOld->getCount()})</a></div>
	    	{if ($newsOld->getCount() == 0)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="news_old">
	    	<ul class=text>
	    	{foreach $newsOld->getItems() as $newOld}
	    		<div id="news_old{$newOld->id}" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">{$newOld->title}</h3>
					</div>
					<div class="modal-body">
						{if ({$newOld->image}!='')}
							<img src="{$web_root}images/news/{$newOld->image}">
						{/if}
						{CUtils::msg_replace({$newOld->file})}
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
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;">Дипломники текущего учебного года: ({$diploms->getCount()})</div>
	    	{if ($diploms->getCount() == 0)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $diploms->getItems() as $diplom}
	    		<li>{$diplom->student_fio} ({$diplom->group_name}),
    				{if (strlen({$diplom->pract_place})>3)} 
    				место практики: <u>{$diplom->pract_place}</u><br> 
    				{else} 
    				<br>
    				{/if}
    			<i>{$diplom->dipl_name}</i></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('dipl_old'); return false;">Дипломники предыдущих учебных лет: ({$diplomsOld->getCount()})</a></div>
	    	{if ($diplomsOld->getCount() == 0)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="dipl_old">
	    	<ul class=text>
	    	{foreach $diplomsOld->getItems() as $diplomOld}
	    		<li>{$diplomOld->student_fio} ({$diplomOld->group_name}),
    				{if (strlen({$diplomOld->pract_place})>3)} 
    				место практики: <u>{$diplomOld->pract_place}</u><br> 
    				{else} 
    				<br>
    				{/if}
    			<i>{$diplomOld->dipl_name}</i></li>
	    	{/foreach}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirs'); return false;" title='срок обучения не истек'>Подготовка аспирантов, текущие: ({$aspirs->getCount()})</a></div>
	    	{if ($aspirs->getCount() == 0)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirs">
	    	<ul class=text>
	    	{foreach $aspirs->getItems() as $aspir}
	    		<li>{$aspir->fio}<br><i>{$aspir->tema}</i></li>
	    	{/foreach}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirsOld'); return false;" title='с истекшим сроком обучения'>Подготовка аспирантов, архив: ({$aspirsOld->getCount()})</a></div>
	    	{if ($aspirsOld->getCount() == 0)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirsOld">
	    	<ul class=text>
	    	{foreach $aspirsOld->getItems() as $aspirOld}
	    		<li>{$aspirOld->fio}<br><i>{$aspirOld->tema}</i></li>
	    	{/foreach}
	    	</ul>
	    	</div>
	    	
	    	<p><div class=text style="font-weight:bold;">Расписание занятий:</div>
			{if ($rasps->getCount() == 0)}
	    		<div class=text>&nbsp;- расписания на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $rasps->getItems() as $rasp}
	    		<li><a href="{$web_root}p_time_table.php?onget=1&idlect={$rasp->id}">расписание занятий</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Вопросы и ответы на них преподавателя: ({$quests->getCount()}) &nbsp; <a href="{$web_root}_modules/_question_add/index.php?action=index&user_id={CRequest::getInt("id")}">Задать вопрос</a></div>
			{if ($quests->getCount() == 0)}
	    		<div class=text>&nbsp;- вопросов с ответами на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $quests->getItems() as $quest}
	    		<li>вопрос: <font color=grey>{$quest->question_text}</font>, ответ: <b>{$quest->answer_text}</b></li>
	    	{/foreach}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Кураторство учебных групп: ({$groups->getCount()})</div>
			{if ($groups->getCount() == 0)}
	    		<div class=text>&nbsp;- записей на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $groups->getItems() as $group}
	    		<li><a href="{$web_root}p_stgroups.php?onget=1&group_id={$group->id}">{$group->name}</a></li>
	    	{/foreach}
	    	</ul>
    	</td>
    	</tr>
    	</table>
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/view.right.tpl"}
{/block}
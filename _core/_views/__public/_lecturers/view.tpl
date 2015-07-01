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
    		<th></th>
    		<th width="100"></th>
    		<th width="20"></th>
            <th></th>
            <th></th>
        </tr>
    	<tr>
    	<td>
			{if ($lect->getBiography()->getCount() == 0)}
        		Биография не выложена</td>
    		{else}
    			{foreach $lect->getBiography()->getItems() as $biogr}
	    			<td>{CHtml::activeAttachPreview("photo", $lect->getPerson(), 100)}</td>
	    			<td></td>
	    			<td>{if ($printFullBox)}
	    					{$biog}
							<p><a href="#modal" data-toggle="modal">Подробнее...</a></p>
	    				{else}
	    					{$biog}
						{/if}
					</td>
	    			<td><div id="modal" class="modal hide fade">
						<div class="modal-header">
						    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <h3 id="myModalLabel">Биография</h3>
						</div>
						<div class="modal-body">
						    {CUtils::msg_replace($biogr->main_text)}
						</div>
						<div class="modal-footer">
						    <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
						</div>
					</div></td>
				{/foreach}
    		{/if}
    	</tr>
    	</table>
    	<br>
    	<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
    	<tr>
    	<td>
	    	<div class=text style="font-weight:bold; text-decoration:underline;">Веб-страницы на портале: ({$lect->getPage()->getCount()})</div>
	    	{if ($lect->getPage()->getCount() == 0)}
	    		<div class=text>&nbsp;- веб-страниц на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getPage()->getItems() as $page}
	    		<li><a href="{$web_root}_modules/_pages/index.php?action=view&id={$page->id}">{$page->title}</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;">Список пособий на портале: ({$lect->getSubjects()->getCount()})</div>
	    	{if ($lect->getSubjects()->getCount() == 0)}
	    		<div class=text>&nbsp;- пособий на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getSubjects()->getItems() as $subject}
	    		<li><a href="{$web_root}p_library.php?onget=1&getdir={$subject->nameFolder}">{$subject->name} ({$subject->f_cnt})</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"> Объявления текущего учебного года: ({$lect->getNewsCurrentYear()->getCount()})</div>
	    	{if ($lect->getNewsCurrentYear()->getCount() == 0)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<ul class=text>
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
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('news_old'); return false;">Объявления прошлых учебных лет: ({$lect->getNewsOld()->getCount()})</a></div>
	    	{if ($lect->getNewsOld()->getCount() == 0)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="news_old">
	    	<ul class=text>
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
	    	
			<p><div class=text style="font-weight:bold;">Дипломники текущего учебного года: ({$lect->getDiplomsCurrentYear()->getCount()})</div>
	    	{if ($lect->getDiplomsCurrentYear()->getCount() == 0)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getDiplomsCurrentYear()->getItems() as $diplom}
	    		<li>{$diplom->student_fio} ({$diplom->group_name}),
    				{if (strlen({$diplom->pract_place})>3)} 
    				место практики: <u>{$diplom->pract_place}</u><br> 
    				{else} 
    				<br>
    				{/if}
    			<i>{$diplom->dipl_name}</i></li>
	    	{/foreach}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('dipl_old'); return false;">Дипломники предыдущих учебных лет: ({$lect->getDiplomsOld()->getCount()})</a></div>
	    	{if ($lect->getDiplomsOld()->getCount() == 0)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="dipl_old">
	    	<ul class=text>
	    	{foreach $lect->getDiplomsOld()->getItems() as $diplomOld}
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
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirs'); return false;" title='срок обучения не истек'>Подготовка аспирантов, текущие: ({$lect->getAspirCurrent()->getCount()})</a></div>
	    	{if ($lect->getAspirCurrent()->getCount() == 0)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirs">
	    	<ul class=text>
	    	{foreach $lect->getAspirCurrent()->getItems() as $aspir}
	    		<li>{$aspir->fio}<br><i>{$aspir->tema}</i></li>
	    	{/foreach}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirsOld'); return false;" title='с истекшим сроком обучения'>Подготовка аспирантов, архив: ({$lect->getAspirOld()->getCount()})</a></div>
	    	{if ($lect->getAspirOld()->getCount() == 0)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirsOld">
	    	<ul class=text>
	    	{foreach $lect->getAspirOld()->getItems() as $aspirOld}
	    		<li>{$aspirOld->fio}<br><i>{$aspirOld->tema}</i></li>
	    	{/foreach}
	    	</ul>
	    	</div>
	    	
	    	<p><div class=text style="font-weight:bold;">Расписание занятий:</div>
			{if ($lect->getTime()->getCount() == 0)}
	    		<div class=text>&nbsp;- расписания на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getTime()->getItems() as $rasp}
	    		<li><a href="{$web_root}p_time_table.php?onget=1&idlect={$rasp->id}">расписание занятий</a></li>
	    	{/foreach}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Вопросы и ответы на них преподавателя: ({$lect->getQuestions()->getCount()}) &nbsp; <a href="{$web_root}_modules/_question_add/index.php?action=index&user_id={CRequest::getInt("id")}">Задать вопрос</a></div>
			{if ($lect->getQuestions()->getCount() == 0)}
	    		<div class=text>&nbsp;- вопросов с ответами на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getQuestions()->getItems() as $quest}
	    		<li>вопрос: <font color=grey>{$quest->question_text}</font>, ответ: <b>{$quest->answer_text}</b></li>
	    	{/foreach}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Кураторство учебных групп: ({$lect->getGroups()->getCount()})</div>
			{if ($lect->getGroups()->getCount() == 0)}
	    		<div class=text>&nbsp;- записей на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{foreach $lect->getGroups()->getItems() as $group}
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
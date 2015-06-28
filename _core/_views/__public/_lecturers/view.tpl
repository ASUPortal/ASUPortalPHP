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
    		{if ($biogs == 0)}
        		Биография не выложена
    		{else}
    			{$pathPhoto}
    			{CLecturersController::biographyView()}
    		{/if}
    		</div><br>
	
	    	<div class=text style="font-weight:bold; text-decoration:underline;">Веб-страницы на портале: ({mysql_num_rows($resPage)})</div>
	    	{if (mysql_num_rows($resPage)<1)}
	    		<div class=text>&nbsp;- веб-страниц на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resPage))}
	    		<li><a href="{$web_root}_modules/_pages/index.php?action=view&id={$a['id']}">{$a['title']}</a></li>
	    	{/while}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;">Список пособий на портале: ({mysql_num_rows($resSubj)})</div>
	    	{if (mysql_num_rows($resSubj)<1)}
	    		<div class=text>&nbsp;- пособий на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resSubj))}
	    		<li><a href='{$web_root}p_library.php?onget=1&getdir={$a['nameFolder']}'>{$a['nameSubject']} ({$a['f_cnt']})</a></li>
	    	{/while}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"> Объявления текущего учебного года: ({mysql_num_rows($resNews)})</div>
	    	{if (mysql_num_rows($resNews)<1)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resNews))}
	    		<div id="news{$a['id']}" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">{$a['title']}</h3>
					</div>
					<div class="modal-body">
						{if ({$a['image']}!='')}
							<img src="{$web_root}images/news/{$a['image']}">
						{/if}
						{CLecturersController::msg_replace($a['file'])}
						{if ({$a['file_attach']}!='')}
							<br><div>Прикреплен файл: <a href="{$web_root}news/attachement/{$a['file_attach']}">
							<img src="{$web_root}images/design/attachment.gif" border=0><b>{$a['file_attach']}</b></a></div>
						{/if}
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
					</div>
				</div>
	    		<li><a href="#news{$a['id']}" data-toggle="modal">{$a['title']} от {$a['date_time']|date_format:"d.m.Y"}</a></li>
	    	{/while}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('news_old'); return false;">Объявления прошлых учебных лет: ({mysql_num_rows($resNewsOld)})</a></div>
	    	{if (mysql_num_rows($resNewsOld)<1)}
	    		<div class=text>&nbsp;- объявлений на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="news_old">
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resNewsOld))}
	    		<div id="news_old{$a['id']}" class="modal hide fade">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h3 id="myModalLabel">{$a['title']}</h3>
					</div>
					<div class="modal-body">
						{if ({$a['image']}!='')}
							<img src="{$web_root}images/news/{$a['image']}">
						{/if}
						{CLecturersController::msg_replace($a['file'])}
						{if ({$a['file_attach']}!='')}
							<br><div>Прикреплен файл: <a href="{$web_root}news/attachement/{$a['file_attach']}">
							<img src="{$web_root}images/design/attachment.gif" border=0><b>{$a['file_attach']}</b></a></div>
						{/if}
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
					</div>
				</div>
	    		<li><a href="#news_old{$a['id']}" data-toggle="modal">{$a['title']} от {$a['date_time']|date_format:"d.m.Y"}</a></li>
	    	{/while}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;">Дипломники текущего учебного года: ({mysql_num_rows($resDipl)})</div>
	    	{if (mysql_num_rows($resDipl)<1)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resDipl))}
	    		<li>{$a['student_fio']} ({$a['group_name']}),
    				{if (strlen({$a['pract_place']})>3)} 
    				место практики: <u>{$a['pract_place']}</u><br> 
    				{else} 
    				<br>
    				{/if}
    			<i>{$a['dipl_name']}</i></li>
	    	{/while}
	    	</ul>
	    	
			<div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('dipl_old'); return false;">Дипломники предыдущих учебных лет: ({mysql_num_rows($resDiplOld)})</a></div>
	    	{if (mysql_num_rows($resDiplOld)<1)}
	    		<div class=text>&nbsp;- дипломников на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="dipl_old">
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resDiplOld))}
	    		<li>{$a['student_fio']} ({$a['group_name']}),
    				{if (strlen({$a['pract_place']})>3)} 
    				место практики: <u>{$a['pract_place']}</u><br> 
    				{else} 
    				<br>
    				{/if}
    			<i>{$a['dipl_name']}</i></li>
	    	{/while}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirs'); return false;" title='срок обучения не истек'>Подготовка аспирантов, текущие: ({mysql_num_rows($resAspir)})</a></div>
	    	{if (mysql_num_rows($resAspir)<1)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirs">
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resAspir))}
	    		<li>{$a['fio']}<br><i>{$a['tema']}</i></li>
	    	{/while}
	    	</ul>
	    	</div>
	    	
			<p><div class=text style="font-weight:bold;"><a href="#" onclick="hide_show('aspirsOld'); return false;" title='с истекшим сроком обучения'>Подготовка аспирантов, архив: ({mysql_num_rows($resAspirOld)})</a></div>
	    	{if (mysql_num_rows($resAspirOld)<1)}
	    		<div class=text>&nbsp;- аспирантов на портале нет</div>
	    	{/if}
	    	<div style="display:none;" id="aspirsOld">
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resAspirOld))}
	    		<li>{$a['fio']}<br><i>{$a['tema']}</i></li>
	    	{/while}
	    	</ul>
	    	</div>
	    	
	    	<p><div class=text style="font-weight:bold;">Расписание занятий:</div>
			{if (mysql_num_rows($resRasp)<1)}
	    		<div class=text>&nbsp;- расписания на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resRasp))}
	    		<li><a href="{$web_root}p_time_table.php?onget=1&idlect={$a['id']}">расписание занятий</a></li>
	    	{/while}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Вопросы и ответы на них преподавателя: ({mysql_num_rows($resQuest)}) &nbsp; <a href="{$web_root}_modules/_question_add/index.php?action=index&user_id={CRequest::getInt("id")}">Задать вопрос</a></div>
			{if (mysql_num_rows($resQuest)<1)}
	    		<div class=text>&nbsp;- вопросов с ответами на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resQuest))}
	    		<li>вопрос: <font color=grey>{$a['question_text']}</font>, ответ: <b>{$a['answer_text']}</b></li>
	    	{/while}
	    	</ul>
	    	
			<p><div class=text style="font-weight:bold;">Кураторство учебных групп: ({mysql_num_rows($resGroup)})</div>
			{if (mysql_num_rows($resGroup)<1)}
	    		<div class=text>&nbsp;- записей на портале нет</div>
	    	{/if}
	    	<ul class=text>
	    	{while ($a=mysql_fetch_array($resGroup))}
	    		<li><a href="{$web_root}p_stgroups.php?onget=1&group_id={$a['id']}">{$a['name']}</a></li>
	    	{/while}
	    	</ul>
    	</td>
    	</tr>
    	</table>
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/view.right.tpl"}
{/block}
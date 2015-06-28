{extends file="_core.3col.tpl"}

{block name="asu_center"}
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <h2 style="text-align:center">Преподаватели</h2>
    <div class=text style="text-align:center">выберите первую букву фамилии преподавателя</div>
    <br>
	<div class=text style="text-align:center">
		<div style="font-size:18pt;">
	        {if (mysql_num_rows($res_rus)>0)}
	        	{while ($a_rus=mysql_fetch_assoc($res_rus))}
	        		{if (array_key_exists($letterId, $firstLet))}
	        			{if ($firstLet[$letterId]==$a_rus['name'])}
	        				<font size=+3>{$a_rus['name']}<font color=#e70013><sub>{$a_rus['cnt']}</sub></font></font>
	        			{else}
	        				<a href="?getsub={array_search($a_rus['name'],$firstLet)}" title="в категории записей: {$a_rus['cnt']}">{$a_rus['name']}<font color=#e70013><sub>{$a_rus['cnt']}</sub></font></a>
	        			{/if}
	        		{else} 
	        			<a href="?getsub={array_search($a_rus['name'],$firstLet)}" title="в категории записей: {$a_rus['cnt']}">{$a_rus['name']}<font color=#e70013><sub>{$a_rus['cnt']}</sub></font></a>
	        		{/if}
	        	{/while}
	        {else}
			<div class=text><b>записей не найдено</b></div>
	        {/if}
	        <br><br><a href="?getallsub=1">все</a><br><BR>
		</div>
	</div>

    {if ($lects->getCount() == 0)}
        Нет объектов для отображения
    {else}
	    {if (CSession::isAuth())}
	    	{include file="_core.searchLocal.tpl"}
	    {/if}
        
        <table class="table">
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $lects->getItems() as $lect}
                <tr>
                    <td>
                    	{$query = mysql_query("select * from biography where user_id={$lect->getId()}")}
	                    {$res=mysql_num_rows($query)}
	                    <a href="index.php?action=view&id={$lect->getId()}">{$lect->FIO}
	        			{if $res==1}
	                    	(+)</a>
	                    {else}
	                    	(-)</a>
	                    {/if}

						{$query = "select count(*) from diploms left join kadri on diploms.kadri_id=kadri.id left join users on users.kadri_id=kadri.id where users.id={$lect->getId()} and users.kadri_id>0"}
	                    {$res=mysql_query($query)}
	        			{$dipl_cnt=mysql_result($res,0)}
	        			{if $dipl_cnt!=0}
	                    	<span class=text style="color:#CCCCCC;"> дипломников({$dipl_cnt})</span>
	                    {/if}
	                    
						{$query = "select count(*) from documents where documents.user_id={$lect->getId()}"}
	                    {$res=mysql_query($query)}
	        			{$doc_cnt=mysql_result($res,0)}
	        			{if $doc_cnt!=0}
	                    	<span class=text style="color:#CCCCCC;"> предметов({$doc_cnt})</span>
	                    {/if}
	                    
						{$query = "select count(*) from news where user_id_insert={$lect->getId()}"}
	                    {$res=mysql_query($query)}
	        			{$news_cnt=mysql_result($res,0)}
	        			{if $news_cnt!=0}
	                    	<span class=text style="color:#CCCCCC;"> объявлений({$news_cnt})</span>
	                    {/if}
	                    
						{$query = "select count(id) from time where id={$lect->getId()} and time.year={CUtils::getCurrentYear()->getId()} and time.month={CUtils::getCurrentYearPart()->getId()}"}
	                    {$res=mysql_query($query)}
	        			{$time_cnt=mysql_result($res,0)}
	        			{if $time_cnt!=0}
	                    	<span class=text style="color:#CCCCCC;"> расписание</span>
	                    {/if}
                    </td>

                </tr>
            {/foreach}
        </table>
        <p class="text" valign="bottom">(+) биография есть (-) биографии нет</p>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/index.right.tpl"}
{/block}
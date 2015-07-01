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
			{if ($resRusLetters)}
	        	{foreach $resRusLetters as $name=>$count}
	        		{if (array_key_exists($letterId, $firstLet))}
	        			{if ($firstLet[$letterId]=={$name})}
	        				<font size=+3>{$name}<font color=#e70013><sub>{$count}</sub></font></font>
	        			{else}
	        				<a href="?getsub={array_search({$name},$firstLet)}" title="в категории записей: {$count}">{$name}<font color=#e70013><sub>{$count}</sub></font></a>
	        			{/if}
	        		{else} 
	        			<a href="?getsub={array_search({$name},$firstLet)}" title="в категории записей: {$count}">{$name}<font color=#e70013><sub>{$count}</sub></font></a>
	        		{/if}
	        	{/foreach}
	        {else}
			<div class=text><b>записей не найдено</b></div>
	        {/if}
	        <br><br><a href="?getallsub=1">все</a><br><BR>
		</div>
	</div>

    {if ($lects->getCount() == 0)}
        Нет объектов для отображения
    {else}
	    {include file="_core.searchLocal.tpl"}
	    	
        <table class="table">
            {counter start=(20 * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $lects->getItems() as $lect}
                <tr>
                    <td>
	                    <a href="index.php?action=view&id={$lect->getId()}">{$lect->FIO}
	        			{if {$lect->getBiography()->getCount()}==1}
	                    	(+)</a>
	                    {else}
	                    	(-)</a>
	                    {/if}

	        			{if {$lect->getDiploms()->getCount()}!=0}
	                    	<span class=text style="color:#CCCCCC;"> дипломников({$lect->getDiploms()->getCount()})</span>
	                    {/if}
	                    
	        			{if {$lect->getDoc()->getCount()}!=0}
	                    	<span class=text style="color:#CCCCCC;"> предметов({$lect->getDoc()->getCount()})</span>
	                    {/if}
	                    
	        			{if {$lect->getNews()->getCount()}!=0}
	                    	<span class=text style="color:#CCCCCC;"> объявлений({$lect->getNews()->getCount()})</span>
	                    {/if}
	        			{if {$lect->getTime()->getCount()}!=0}
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
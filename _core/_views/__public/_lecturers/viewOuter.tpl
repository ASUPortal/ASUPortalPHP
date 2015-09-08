{extends file="_core.3col.tpl"}

{block name="asu_center"}
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <h2 style="text-align:center">{$lect->FIO}</h2>
    	<table class="table table-striped table-bordered table-hover table-condensed">
	    	<tr>
	    	<td>
				{if ($lect->getBiographies()->getCount() == 0)}
	        		Биография не выложена
	    		{else}
	    			{foreach $lect->getBiographies()->getItems() as $biogr}
						{CUtils::getReplacedMessage($biogr->main_text)}
					{/foreach}
	    		{/if}
	    	</td>
	    	</tr>
    	</table>
    	{if {$lect->getSchedule()->getCount()}!=0}
			{foreach $lect->getSchedule()->getItems() as $rasp}
				<div><b><a href="{$web_root}p_time_table.php?onget=1&idlect={$rasp->id}">Расписание</a></b></div>
			{/foreach}
		{else}
			Расписания на портале нет<br>
		{/if}
    	<br>
    	    {include file="__public/_lecturers/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/view.right.tpl"}
{/block}
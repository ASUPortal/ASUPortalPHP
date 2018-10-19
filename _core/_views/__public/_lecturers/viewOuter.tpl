{extends file="_core.3col.tpl"}

{block name="asu_center"}
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <h2 style="text-align:center">{$lect->FIO}</h2>
    	<table>
    	<tr>
    		<th></th>
    		<th width="100"></th>
    		<th width="20"></th>
            <th></th>
        </tr>
    	<tr>
    	<td>
    		{if ($lect->getBiographies()->getCount() == 0)}
        		Биография не выложена</td>
    		{else}
    			{foreach $lect->getBiographies()->getItems() as $biogr}
	    			<td>{CHtml::activeAttachPreview("image", $biogr, 100)}</td>
	    			<td></td>
	    			<td>{CUtils::getReplacedMessage($biogr->main_text)}</td>
				{/foreach}
    		{/if}
    	</tr>
    	</table>
    	{if {$lect->getSchedule()->getCount()}!=0}
			{foreach $lect->getSchedule()->getItems() as $rasp}
				<div><b><a href="{$web_root}_modules/_schedule/public.php?action=viewLecturers&id={$rasp->id}">Расписание</a></b></div>
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
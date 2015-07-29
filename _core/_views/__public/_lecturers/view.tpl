{extends file="_core.3col.tpl"}

{block name="asu_center"}
	{if (CSession::isAuth())}
    	{CHtml::helpForCurrentPage()}
    {/if}
    <h2 style="text-align:center">{$lect->fio}</h2>

    	<table>
    	<tr>
    		<th></th>
    		<th width="100"></th>
    		<th width="20"></th>
            <th></th>
            <th></th>
        </tr>
    	<tr>
    	<td>
			{if ($lect->getBiographies()->getCount() == 0)}
        		Биография не выложена</td>
    		{else}
    			{foreach $lect->getBiographies()->getItems() as $biogr}
	    			<td>{CHtml::activeAttachPreview("photo", $lect, 100)}</td>
	    			<td></td>
	    			<td>{CUtils::getReplacedMessage($biogr->main_text)}</td>
				{/foreach}
    		{/if}
    	</tr>
    	</table>
    	<br>
    	    {include file="__public/_lecturers/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/view.right.tpl"}
{/block}
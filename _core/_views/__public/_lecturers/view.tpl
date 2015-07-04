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
			{if ($lect->getBiography()->getCount() == 0)}
        		Биография не выложена</td>
    		{else}
    			{foreach $lect->getBiography()->getItems() as $biogr}
	    			<td>{CHtml::activeAttachPreview("photo", $lect, 100)}</td>
	    			<td></td>
	    			<td>
	    				{if (mb_strlen($biogr->main_text) > 500)}
    						{mb_substr(CUtils::msg_replace($biogr->main_text), 0, 500)}
							<p><a href="#modal" data-toggle="modal">Подробнее...</a></p>
	    				{else}
	    					{CUtils::msg_replace($biogr->main_text)}
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
    	    {include file="__public/_lecturers/form.tpl"}
{/block}

{block name="asu_right"}
    {include file="__public/_lecturers/view.right.tpl"}
{/block}
{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Протоколы кафедры</h2>

    {CHtml::helpForCurrentPage()}
    
    <table border="0" width="100%" class="tableBlank">
		<tr>
			<td width="30%">			
				<form class="form-horizontal">
			      <div class="control-group">
			       	<label for="onControl" class="control-label">Отражать протоколы только на контроле</label>
			      	<div class="controls">
						{CHtml::checkBox("onControl", 1, $onControl)}
			        </div>
			      </div>
				</form>
	    	</td>
	    	<td valign="top">
	    		<p align="left">
					<span><img src="{$web_root}images/del_filter.gif" style="cursor: pointer; " onclick="removeFilter(); return false; " title="очистить фильтры"/></span>
				</p>
			</td>
		</tr>
    </table>
    
    {include file="_core.searchLocal.tpl"}
        
    <script>
	    function removeFilter() {
	        var action = "?action=index";
	        window.location.href = action;
	    }
    	jQuery(document).ready(function(){
    		jQuery("#onControl").change(function(){
    				{if $onControl}
						window.location.href = web_root + "_modules/_protocols_dep/index.php";
    				{else}
    					window.location.href = web_root + "_modules/_protocols_dep/index.php?action=index&onControl=1";
    				{/if}
    		});
        });
    </script>

	{if ($protocols->getCount() == 0)}
        Нет объектов для отображения
    {else}
		<form action="index.php" method="post" id="mainView">
			<table class="table table-striped table-bordered table-hover table-condensed">
			    <tr>
			        <th width="16">&nbsp;</th>
			        <th>{CHtml::activeViewGroupSelect("id", $protocols->getFirstItem(), true)}</th>
					<th width="16">#</th>
					<th width="16">&nbsp;</th>
					<th>{CHtml::tableOrder("num", $protocols->getFirstItem())}</th>
			        <th>{CHtml::tableOrder("date_text", $protocols->getFirstItem())}</th>
			        <th>{CHtml::tableOrder("program_content", $protocols->getFirstItem())}</th>
			        <th>На контроле (пункт, ФИО, содержание)</th>
			        <th>{CHtml::tableOrder("comment", $protocols->getFirstItem())}</th>
			    </tr>
			    {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
			    {foreach $protocols->getItems() as $protocol}
			    <tr>
			        <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить протокол №{$protocol->num}')) { location.href='?action=delete&id={$protocol->id}'; }; return false;"></a></td>
					<td>{CHtml::activeViewGroupSelect("id", $protocol, false, true)}</td>
					<td>{counter}</td>
					<td><a href="index.php?action=edit&id={$protocol->getId()}" class="icon-pencil" title="правка"></a></td>
			        <td>{$protocol->num}</td>
			        <td>
			            <a href="?action=view&id={$protocol->getId()}" title="просмотреть">{$protocol->date_text}</a>
			        </td>
			        <td>{str_replace("\n", "<br>", $protocol->program_content)}</td>
			        <td width=30%>
				        {foreach $protocol->control->getItems() as $point}
				        	{$point->ordering}&nbsp;<b>{$point->person->fio_short}</b>&nbsp;{$point->text_content}
				        {/foreach}
			        </td>
			        <td>{$protocol->comment}</td>
			    </tr>
			    {/foreach}
			</table>
		</form>

    	{CHtml::paginator($paginator, "?action=index")}
	{/if}
{/block}

{block name="asu_right"}
	{include file="_protocols_dep/protocol/common.right.tpl"}
{/block}
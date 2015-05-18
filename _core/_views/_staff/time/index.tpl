{extends file="_core.3col.tpl"}

{block name="localSearchContent"}	
	<form class="form-horizontal">
		<div class="control-group">
			<label for="PPS" class="control-label">Отобразить только ППС</label>
			<div class="controls">
				{CHtml::checkBox("PPS", 1, $PPS)}
			</div>
		</div>
		<div class="control-group">
			<label for="UVP" class="control-label">Отобразить только УВП</label>
	      	<div class="controls">
				{CHtml::checkBox("UVP", 1, $UVP)}
			</div>
		</div>
	</form>
{/block}

{block name="asu_center"}
    <h2>Табель сотрудников</h2>
	{CHtml::helpForCurrentPage()}
	{include file="_core.searchLocal.tpl"}

    <script>
    	jQuery(document).ready(function(){
    		jQuery("#PPS").change(function(){
    			{if $PPS}
					window.location.href = web_root + "_modules/_staff/time.php";
    			{else}
    				window.location.href = web_root + "_modules/_staff/time.php?action=index&PPS=1";
    			{/if}
    		});
    		jQuery("#UVP").change(function(){
    			{if $UVP}
					window.location.href = web_root + "_modules/_staff/time.php";
				{else}
					window.location.href = web_root + "_modules/_staff/time.php?action=index&UVP=1";
				{/if}	
			});
        });
    </script>

    {if ($persons->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="40">№ п/п</th>
                    <th>{CHtml::tableOrder("fio_short", $persons->getFirstItem(), true)}</th>
                    <th>{CHtml::tableOrder("dolgnost", $persons->getFirstItem())}</th>
                    <th>{CHtml::tableOrder("stavka", $persons->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $persons->getItems() as $person}
                <tr>
                    <td>{counter}</td>
                    <td><a href="{$web_root}_modules/_orders/index.php?action=view&id={$person->getId()}">{$person->fio}</a></td>
                    <td>
                    	{if $person->dolgnost != 0}
	                		{$person->getPost()->getValue()}
	                	{/if}
	                </td>
                    <td>{$person->getOrdersRate()}<sup style="color:grey;">{$person->getOrdersCount()}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        {CHtml::paginator($paginator, "time.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_staff/time/common.right.tpl"}
{/block}

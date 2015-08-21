{extends file="_core.3col.tpl"}

{block name="localSearchContent"}
	<form class="form-horizontal">
      <div class="control-group">
       	<label for="showall" class="control-label">Показывать комиссии прошлых лет</label>
      	<div class="controls">
			{CHtml::checkBox("showall", 1, $showAll)}
        </div>
      </div>
	</form>
{/block}

{block name="asu_center"}
    <h2>Комиссии по предзащите ВКР</h2>

    {CHtml::helpForCurrentPage()}
    {include file="_core.searchLocal.tpl"}
	
	<script>
    	jQuery(document).ready(function(){
    		jQuery("#showall").change(function(){
    			//var requestParams = array();
    			//$.each(CRequest::getGlobalRequestVariables()->getItems(), function(key, value) {
    				//requestParams[] = key + "=" + value;
    				{if $showAll}
						window.location.href = web_root + "_modules/_diploms/preview_comm.php"; //+ implode("&", requestParams);
    				{else}
    					window.location.href = web_root + "_modules/_diploms/preview_comm.php?action=index&showAll=1"; //+ implode("&", requestParams);
    				{/if}
    		});
            jQuery("#selectAll").change(function(){
            	var items = jQuery("input[name='selectedDoc[]']")
                for (var i = 0; i < items.length; i++) {
                    items[i].checked = this.checked;
                }
            });
        });
    </script>

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("name", $commissions->getFirstItem())}</th>
			<th>{CHtml::tableOrder("person.fio", $commissions->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("date_act", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $commissions->getFirstItem())}</th>
            <th>{CHtml::tableOrder("members", $commissions->getFirstItem())}</th>
            <th><input type="checkbox" id="selectAll"></th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $commissions->getItems() as $commission}
            <tr>
                <td valign="top"><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить комиссию {$commission->title}')) { location.href='?action=delete&id={$commission->getId()}'; }; return false;"></a></td>
                <td valign="top">{counter}</td>
                <td valign="top"><a href="?action=edit&id={$commission->getId()}">{$commission->name}</a></td>
                <td valign="top">
                    {if !is_null($commission->secretar)}
                        {$commission->secretar->fio}
                    {/if}
                </td>
                <td valign="top">
                    {$commission->date_act|date_format:"d.m.Y"}
                </td>
                <td valign="top">
                    {$commission->comment}
                </td>
                <td valign="top">
                	<ul>
                	{foreach $commission->members->getItems() as $member}
                		<li>{$member->getName()}</li>
               		{/foreach}
               		</ul>
                </td>
                <td>
                    <input type="checkbox" value="{$commission->getId()}" name="selectedDoc[]">
                </td>
            </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
    {include file="_diploms/preview_commission/index.right.tpl"}
{/block}
{extends file="_core.component.tpl"}

{block name="asu_center"}
	{if isset($error)}
		<ul><font color="#FF0000">{$error}</font></ul>
	{else}
		<h4>Шаблон {CPrintManager::getForm(CRequest::getInt("template"))->title}</h4>
		<ul>
	        <li>
	            Всего описателей в шаблоне: {$countFields}
	        </li>
	        <li>
	            Из них не заполнено текстовых описателей: {$countEmptyTextFields}, табличных: {$countEmptyTableFields}
	        </li>
	        <li>
	            Процент заполнения шаблона: {$percentFull}%
	        </li>
	    </ul>
	    {if count($emptyTextFields) != 0}
		    <div class="modal-header">
		    	<h4 id="myModalLabel">Незаполненные текстовые описатели</h4>
		    </div>
		    <div class="modal-body">
		    	<table class="table table-striped table-bordered table-hover table-condensed">
				    <tr>
				        <th>#</th>
				        <th>Название описателя</th>
				    </tr>
			    {counter start=0 print=false}
			    {foreach $emptyTextFields as $key=>$name}
			        <tr>
			            <td>{counter}</td>
			            <td>{$name}</td>
			        </tr>
			    {/foreach}
				</table>
		    </div>
	    {/if}
	    {if count($emptyTableFields) != 0}
		    <div class="modal-header">
		    	<h4 id="myModalLabel">Незаполненные табличные описатели</h4>
		    </div>
		    <div class="modal-body">
		    	<table class="table table-striped table-bordered table-hover table-condensed">
				    <tr>
				        <th>#</th>
				        <th>Название описателя</th>
				    </tr>
			    {counter start=0 print=false}
			    {foreach $emptyTableFields as $key=>$name}
			        <tr>
			            <td>{counter}</td>
			            <td>{$name}</td>
			        </tr>
			    {/foreach}
				</table>
		    </div>
	    {/if}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_corriculum/_workplan/status/common.right.tpl"}
{/block}
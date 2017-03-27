{extends file="_core.component.tpl"}

{block name="asu_center"}
    {CHtml::helpForCurrentPage()}
    
	{if $protocolPoints->getItems() == 0}
	    Решения еще не добавлены
	{else}
	    <table class="table table-striped table-bordered table-hover table-condensed">
	        <tbody>
	        {foreach $protocolPoints->getItems() as $point}
	            <tr>
	                <td rowspan="2"><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить пункт')) { location.href='point.php?action=delete&id={$point->getId()}'; }; return false;"></a></td>
	                <td rowspan="2"><a href="point.php?action=edit&id={$point->getId()}" class="icon-pencil"></a></td>
	                <td rowspan="2">{$point->ordering}</td>
	                <td><strong>Слушали:</strong></td>
	                <td>
	                	<b>{$point->person->fio_short}</b>
		                {$point->text_content}
	                </td>
	            </tr>
	            <tr>
	                <td><strong>Постановили:</strong></td>
	                <td>
	                    {if !is_null($point->decision)}
	                        {$point->decision->getValue()}
	                    {/if}
	                    {$point->opinion_text}
	                </td>
	            </tr>
	        {/foreach}
	        </tbody>
	    </table>
	{/if}
    
{/block}

{block name="asu_right"}
	{include file="_protocols_dep/agendaPoint/common.right.tpl"}
{/block}
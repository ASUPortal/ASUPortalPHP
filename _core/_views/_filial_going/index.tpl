{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>Командировочные удостоверения</h2>

    {CHtml::helpForCurrentPage()}

    {if ($filialGoings->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
				<th>{CHtml::activeViewGroupSelect("id", $filialGoings->getFirstItem(), true)}</th>
	            <th></th>
	            <th></th>
	            <th></th>
	            <th>{CHtml::tableOrder("person.fio", $filialGoings->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("filial.name", $filialGoings->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("day_cnt", $filialGoings->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("hours_cnt", $filialGoings->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("day_start", $filialGoings->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("day_end", $filialGoings->getFirstItem())}</th>
	            <th>{CHtml::tableOrder("filial_act.name", $filialGoings->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("transport.name", $filialGoings->getFirstItem(), true)}</th>
	            <th>{CHtml::tableOrder("comment", $filialGoings->getFirstItem())}</th>
        	</tr>
        	{counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $filialGoings->getItems() as $filialGoing}
                <tr>
                	<td>{CHtml::activeViewGroupSelect("id", $filialGoing, false, true)}</td>
                    <td>
                    	{if !is_null($filialGoing->person)}
                    		<a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить командировочное удостоверение {$filialGoing->person->getNameShort()}')) { location.href='?action=delete&id={$filialGoing->id}'; }; return false;"></a>
                    	{/if}
                    </td>
                    <td>{counter}</td>
                    <td><a href="index.php?action=edit&id={$filialGoing->getId()}" class="icon-pencil" title="правка"></a></td>
					<td>
						{if !is_null($filialGoing->person)}
							{$filialGoing->person->getNameShort()}
						{/if}
					</td>
					<td>
						{if !is_null($filialGoing->filial)}
							{$filialGoing->filial->getValue()}
						{/if}
					</td>
					<td>{$filialGoing->day_cnt}</td>
					<td>{$filialGoing->hours_cnt}</td>
					<td>{$filialGoing->day_start}</td>
					<td>{$filialGoing->day_end}</td>
					<td>
						{if !is_null($filialGoing->filial_act)}
							{$filialGoing->filial_act->getValue()}
						{/if}
					</td>
					<td>
						{if !is_null($filialGoing->transport)}
							{$filialGoing->transport->getValue()}
						{/if}
					</td>
					<td>{$filialGoing->comment}</td>
                </tr>
            {/foreach}
        </table>
        {CHtml::paginator($paginator, "?action=index")}
    {/if}
{/block}

{block name="asu_right"}
	{include file="_filial_going/index.right.tpl"}
{/block}


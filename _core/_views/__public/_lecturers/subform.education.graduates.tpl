{if ($lect->getGraduatesCurrentYear()->getCount() == 0)}
	дипломников на портале нет
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
	<tr>
	          <th>#</th>
	          <th>ФИО</th>
	          <th>Группа</th>
	          <th>Место практики</th>
	          <th>Тема диплома</th>
	</tr>
{$i = 1}
{foreach $lect->getGraduatesCurrentYear()->getItems() as $diplom}
	<tr>
	   	<td>{$i++}</td>
	   	<td>
	   		{if !is_null($diplom->student)}
	   			{$diplom->student->getName()}
	   		{/if}
	   	</td>
	   	<td>
			{if !is_null($diplom->student)}
				{if !is_null($diplom->student->getGroup())}
					{$diplom->student->getGroup()->getName()}
				{/if}
			{/if}
	   	</td>
	   	<td>
			{if is_null($diplom->practPlace)}
				{if (strlen({$diplom->pract_place})>3)}
					{$diplom->pract_place}
				{/if}
			{else}
				{if (strlen({$diplom->practPlace->getValue()})>3)}
					{$diplom->practPlace->getValue()}
				{/if}
			{/if}
		</td>
	   	<td>{$diplom->dipl_name}</td>
	</tr>
{/foreach}
</table>
{/if}
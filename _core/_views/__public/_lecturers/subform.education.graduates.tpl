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
	   		{if ($diplom->student_id !=0 )}
	   			{if !is_null(CStaffManager::getStudent($diplom->student_id))}
	   				{CStaffManager::getStudent($diplom->student_id)->fio}
	   			{/if}
	   		{/if}
	   	</td>
	   	<td>
	   		{if !is_null(CStaffManager::getStudent($diplom->student_id))}
		   		{if !is_null(CStaffManager::getStudent($diplom->student_id)->getGroup())}
					{CStaffManager::getStudent($diplom->student_id)->getGroup()->name}
				{/if}
			{/if}
	   	</td>
	   	<td>
	   		{if ($diplom->pract_place_id != 0)}
	   			{if !is_null(CTaxonomyManager::getPracticePlace($diplom->pract_place_id))}
					{if (strlen({CTaxonomyManager::getPracticePlace($diplom->pract_place_id)->name})>3)}
						{CTaxonomyManager::getPracticePlace($diplom->pract_place_id)->name}
					{/if}
				{/if}
			{elseif (strlen({$diplom->pract_place})>3)}
				{$diplom->pract_place}
			{/if}
		</td>
	   	<td>{$diplom->dipl_name}</td>
	</tr>
{/foreach}
</table>
{/if}
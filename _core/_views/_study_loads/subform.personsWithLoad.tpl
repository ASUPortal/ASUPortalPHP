{function name=clearNullValues level=0}
  {if (floatval(str_replace(',','.',$number)) == 0 or $number == 0)}
     &nbsp;
  {else}
     {$number}
  {/if}
{/function}

<form action="index.php" method="post" id="withLoadView">
    <table class="table table-striped table-bordered table-hover table-condensed" id="dataTable">
        <thead>
	        <tr>
	            <th></th>
	            <th>#</th>
	            <th>ФИО преподавателя</th>
	            <th>долж.</th>
	            <th>ставка факт</th>
	            <th>ставка план</th>
	            <th>число групп</th>
	            <th>число студ.</th>
				{foreach CStudyLoadService::getStudyWorksTotalTitles() as $title}
					<th>{$title}</th>
				{/foreach}
	            <th>осн.</th>
	            <th>доп.</th>
	            <th>надбавка</th>
	            <th>почасовка</th>
	            <th>всего часов в году</th>
	        </tr>
        </thead>
        <tbody>
	        {counter start=0 print=false}
	        {foreach $persons as $person}
		        <tr>
		            <td><a href="#"><i title="Добавить" class="icon-plus" onclick="window.open('{$web_root}_modules/_study_loads/index.php?action=add&kadri_id={$person["kadri_id"]}&year_id={$person["year_id"]}')"></i></a></td>
		            <td>{counter}</td>
		            <td><a href="?action=editLoads&kadri_id={$person['kadri_id']}&year_id={$person['year_id']}&base=1&additional=1&premium=1&byTime=1" title="{$person['fio']}">{$person['fio_short']}</a></td>
		            <td>{$person['dolgnost']}</td>
		            {if ($person['rate'] != 0)}
			            <td>{number_format($person['hours_sum']/$person['rate'],2,',','')}</td>
			        {else}
			            <td>&nbsp;</td>
			        {/if}
			        {if ($person['rate_sum'] != 0)}
			            <td>{number_format($person['rate_sum'],2,',','')}<sup>{$person['ord_cnt']}</sup></td>
			        {else}
			            <td>&nbsp;</td>
			        {/if}
		            <td>{$person['groups_cnt_sum_']}</td>
		            <td>{$person['stud_cnt_sum_']}</td>
		            {foreach CStudyLoadService::getStudyWorksTotalValues($person['kadri_id'], $person['year_id'], $isBudget, $isContract) as $typeId=>$rows}
						{foreach $rows as $kindId=>$value}
							{if !in_array($kindId, array(0))}
								<td>{clearNullValues number=number_format($value,1,',','') level=0}</td>
							{/if}
		                {/foreach}
		            {/foreach}
		            <td>{clearNullValues number=number_format($person['hours_sum_base'],1,',','') level=0}</td>
		            <td>{clearNullValues number=number_format($person['hours_sum_additional'],1,',','') level=0}</td>
		            <td>{clearNullValues number=number_format($person['hours_sum_premium'],1,',','') level=0}</td>
		            <td>{clearNullValues number=number_format($person['hours_sum_by_time'],1,',','') level=0}</td>
		            <td>{clearNullValues number=number_format($person['hours_sum'],1,',','') level=0}</td>
		        </tr>
	        {/foreach}
        </tbody>
        <tr bgcolor="#ff9966">
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
            <td><b>Итого</b></td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			{if (CSession::getCurrentUser()->getLevelForCurrentTask() == {$ACCESS_LEVEL_READ_OWN_ONLY} or CSession::getCurrentUser()->getLevelForCurrentTask() == {$ACCESS_LEVEL_WRITE_OWN_ONLY})}
	            {foreach CStudyLoadService::getAllStudyWorksTotalValuesByPerson($person['kadri_id'], $person['year_id'], $isBudget, $isContract) as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
	        {else}
	            {foreach CStudyLoadService::getAllStudyWorksTotalValues($person['year_id'], $isBudget, $isContract) as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
	        {/if}
			<td><b>{clearNullValues number=number_format($mainTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($additionalTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($premiumTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($byTimeTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($sumTotal,1,',','') level=0}</b></td>
		</tr>
    </table>
</form>
{function name=clearNullValues level=0}
  {if (floatval(str_replace(',','.',$number)) == 0 or $number == 0)}
     &nbsp;
  {else}
     {$number}
  {/if}
{/function}

<form action="index.php" method="post" id="withLoadView">
    <table rel="stripe" class="table table-striped table-bordered table-hover" border="1" id="dataTable">
        <thead>
	        <tr>
	            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
					<th>&nbsp;</th>
	            {/if}
	            <th>{CHtml::checkboxGroupSelect("", true)}</th>
	            <th>#</th>
	            <th>ФИО преподавателя</th>
	            <th>долж.</th>
	            <th>ставка план</th>
	            <th>ставка факт</th>
	            <th>осн.</th>
	            <th>доп.</th>
	            <th>надбавка</th>
	            <th>почасовка</th>
	            <th>дип. (зима)</th>
	            <th>дип. (лето)</th>
				{foreach CStudyLoadService::getStudyWorksTotalTitles() as $title}
					<th>{$title}</th>
				{/foreach}
	            <th>всего часов в году</th>
	        </tr>
        </thead>
        <tbody>
	        {counter start=0 print=false}
	        {foreach $persons as $person}
		        {$parameters["kadri_id"] = $person->personId}
		        <tr>
		            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
			            <td rel="stripe"><a href="#"><i title="Добавить" class="icon-plus" onclick="window.open('{$web_root}_modules/_study_loads/index.php?action=add&kadri_id={$person->personId}&year_id={$person->yearId}')"></i></a></td>
		            {/if}
		            <td rel="stripe">{CHtml::checkboxGroupSelect(urlencode(serialize($parameters)))}</td>
		            <td class="count" rel="stripe">{counter}</td>
		            <td rel="stripe"><a href="?action=editLoads&kadri_id={$person->personId}&year_id={$person->yearId}&base=1&additional=1&premium=1&byTime=1" title="{$person->personName}">{$person->personShortName}</a></td>
		            <td rel="stripe">{$person->personPost}</td>
		            {if ($person->rateSum != 0)}
						<td rel="stripe">{number_format($person->rateSum,2,',','')}<sup><a href="{$web_root}_modules/_orders/index.php?action=view&id={$person->personId}" title="{implode('&#013;', CStaffManager::getPerson($person->personId)->getActiveOrdersListWithRate())}" target="_blank">{$person->orderCount}</a></sup></td>
		            {else}
						<td rel="stripe">&nbsp;</td>
		            {/if}
		            {if (CStaffService::getHoursPersonInHoursRateByPost($person->personPostId, $year) != 0)}
						<td rel="stripe">{number_format($person->workloadSum/CStaffService::getHoursPersonInHoursRateByPost($person->personPostId, $year),2,',','')}</td>
					{else}
						<td rel="stripe">{number_format(1, 2, ',', '')}</td>
					{/if}
			        <td rel="stripe">{clearNullValues number=number_format($person->hoursSumBase,1,',','') level=0}</td>
		            <td rel="stripe">{clearNullValues number=number_format($person->hoursSumAdditional,1,',','') level=0}</td>
		            <td rel="stripe">{clearNullValues number=number_format($person->hoursSumPremium,1,',','') level=0}</td>
		            <td rel="stripe">{clearNullValues number=number_format($person->hoursSumByTime,1,',','') level=0}</td>
		            <td rel="stripe">{$person->diplCountWinter}</td>
		            <td rel="stripe">{$person->diplCountSummer}</td>
		            {foreach CStudyLoadService::getStudyWorksTotalValues($person->personId, $selectedYear, $isBudget, $isContract) as $typeId=>$rows}
						{foreach $rows as $kindId=>$value}
							{if !in_array($kindId, array(0))}
								<td rel="stripe">{clearNullValues number=number_format($value,1,',','') level=0}</td>
							{/if}
		                {/foreach}
		            {/foreach}
		            <td rel="stripe">{clearNullValues number=number_format($person->workloadSum,1,',','') level=0}</td>
		        </tr>
	        {/foreach}
        </tbody>
        <tr>
        	{if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
            	<td>&nbsp;</td>
        	{/if}
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
            <td><b>Итого</b></td>
			<td>&nbsp;</td>
			<td><b>{clearNullValues number=$rateSum level=0}</b></td>
			<td><b>{clearNullValues number=$rateSumFact level=0}</b></td>
			<td><b>{clearNullValues number=number_format($mainTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($additionalTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($premiumTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format($byTimeTotal,1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=$diplCountWinterSum level=0}</b></td>
			<td><b>{clearNullValues number=$diplCountSummerSum level=0}</b></td>
			{if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_OWN_ONLY, $ACCESS_LEVEL_WRITE_OWN_ONLY]))}
	            {foreach CStudyLoadService::getAllStudyWorksTotalValuesByPerson($person->personId, $selectedYear, $isBudget, $isContract)->getItems() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
	        {else}
	            {foreach CStudyLoadService::getAllStudyWorksTotalValues($selectedYear, $isBudget, $isContract)->getItems() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
	        {/if}
			<td><b>{clearNullValues number=number_format($sumTotal,1,',','') level=0}</b></td>
		</tr>
    </table>
</form>

<script>
	$(document).ready(function() {
		updateTableNumeration();
		jQuery("input[class='filter']").on("change", function(){
			$.ajax({
				success: function(data) {
					updateTableNumeration();
				}
			});
		});
		jQuery(".header").on("click", function(){
			$.ajax({
				success: function(data) {
					updateTableNumeration();
				}
			});
		});
		function updateTableNumeration() {
			$('.table[rel="stripe"] tbody tr:not([style="display: none;"])').each(function(i) {
				$(this).find('.count').text(i+1);
				if (i % 2 === 0) {
					$(this).find("td[rel='stripe']").css('background', '#c5d0e6');
				} else {
					$(this).find("td[rel='stripe']").css('background', '#DFEFFF');
				}
			});
		}
	});
</script>
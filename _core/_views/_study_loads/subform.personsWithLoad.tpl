<form action="index.php" method="post" id="withLoadView">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>ФИО преподавателя</th>
            <th>долж.</th>
            <th>ставка факт</th>
            <th>ставка план</th>
            <th>число групп</th>
            <th>число студ.</th>
			{foreach CStudyLoadService::getStudyWorksTotalTitles($persons[0]['kadri_id'], $persons[0]['year_id'], $isBudget, $isContract) as $value}
				<th>{$value}</th>
			{/foreach}
            <th>осн.</th>
            <th>доп.</th>
            <th>надбавка</th>
            <th>почасовка</th>
            <th>всего часов в году</th>
        </tr>
        {counter start=0 print=false}
        {foreach $persons as $person}
	        <tr>
	            <td><a href="#"><i title="Добавить" class="icon-plus" onclick="window.open('{$web_root}_modules/_study_loads/index.php?action=add&kadri_id={$person["kadri_id"]}&year_id={$person["year_id"]}')"></i></a></td>
	            <td>{counter}</td>
	            <td><a href="?action=editLoads&kadri_id={$person['kadri_id']}&year_id={$person['year_id']}" title="{$person['fio']}">{$person['fio_short']}</a></td>
	            <td>{$person['dolgnost']}</td>
	            {if ($person['rate'] != 0)}
		            <td>{round($person['hours_sum']/$person['rate'], 2)}</td>
		        {else}
		            <td>&nbsp;</td>
		        {/if}
		        {if ($person['rate_sum'] != 0)}
		            <td>{$person['rate_sum']}<sup>{$person['ord_cnt']}</sup></td>
		        {else}
		            <td>&nbsp;</td>
		        {/if}
	            <td>{$person['groups_cnt_sum_']}</td>
	            <td>{$person['stud_cnt_sum_']}</td>
				{foreach CStudyLoadService::getStudyWorksTotalValues($person['kadri_id'], $person['year_id'], $isBudget, $isContract) as $value}
					<td>{$value}</td>
				{/foreach}
	            <td>{number_format($person['hours_sum_base'],1,',','')}</td>
	            <td>{number_format($person['hours_sum_additional'],1,',','')}</td>
	            <td>{number_format($person['hours_sum_premium'],1,',','')}</td>
	            <td>{number_format($person['hours_sum_by_time'],1,',','')}</td>
	            <td>{number_format($person['hours_sum'],1,',','')}</td>
	        </tr>
        {/foreach}
        <tr bgcolor="#ff9966">
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
            <td><b>Итого</b></td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><b>{$lectsTotal}</b></td>
			<td><b>{$diplTotal}</b></td>
			<td><b>{number_format($mainTotal,1,',','')}</b></td>
			<td><b>{number_format($additionalTotal,1,',','')}</b></td>
			<td><b>{number_format($premiumTotal,1,',','')}</b></td>
			<td><b>{number_format($byTimeTotal,1,',','')}</b></td>
			<td><b>{number_format($sumTotal,1,',','')}</b></td>
		</tr>
    </table>
</form>
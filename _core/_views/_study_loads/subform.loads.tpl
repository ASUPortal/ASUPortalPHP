<style>
	.vert-text {
		-webkit-transform: rotate(180deg); 
		-moz-transform: rotate(180deg);
		-ms-transform: rotate(180deg);
		-o-transform: rotate(180deg);
		transform: rotate(180deg);
		
		writing-mode:tb-rl;
		height:350px;
	}
</style>

<form action="index.php" method="post" id="{$loadsId}">
    <table class="table table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th style="vertical-align:middle; text-align:center;">#</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("discipline_id", $studyLoads->getFirstItem())}</th>
            <th><div class="vert-text">Факультет</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("speciality_id", $studyLoads->getFirstItem())}</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("level_id", $studyLoads->getFirstItem())}</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("groups_count", $studyLoads->getFirstItem())}</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("students_count", $studyLoads->getFirstItem())}</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("load_type_id", $studyLoads->getFirstItem())}</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("comment", $studyLoads->getFirstItem())}</div></th>
	            {foreach $studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if in_array($kindId, array(0))}
							<th><div class="vert-text">{$value}</div></th>
						{/if}
	                {/foreach}
	            {/foreach}
            <th><div class="vert-text">Всего</div></th>
            <th><div class="vert-text">{CHtml::tableOrder("on_filial", $studyLoads->getFirstItem())}</div></th>
        </tr>
        {counter start=0 print=false}
        {foreach $studyLoads->getItems() as $studyLoad}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузку')) { location.href='?action=delete&id={$studyLoad->getId()}'; }; return false;"></a></td>
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $studyLoad, false, true)}</td>
	            <td><a href="?action=edit&id={$studyLoad->getId()}" title="{", "|join:CStudyLoadService::getLecturersNameByDiscipline($studyLoad->discipline)}">{$studyLoad->discipline->getValue()}</a></td>
	            <td>ИРТ</td>
	            <td>{$studyLoad->direction->getValue()}</td>
	            <td>{$studyLoad->studyLevel->name}</td>
	            <td>{$studyLoad->groups_count}</td>
	            <td>{$studyLoad->students_count + $studyLoad->students_contract_count}</td>
	            <td>{$studyLoad->studyLoadType->name}</td>
	            <td>{$studyLoad->comment}</td>
		            {foreach $studyLoad->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
						{foreach $rows as $kindId=>$value}
							{if !in_array($kindId, array(0))}
								<td>{CStringUtils::numLocal($value)}</td>
							{/if}
		                {/foreach}
		            {/foreach}
	            <td>{CStringUtils::numLocal($studyLoad->getSumWorksValue())}</td>
	            <td>{CStringUtils::numLocal($studyLoad->on_filial)}</td>
	        </tr>
        {/foreach}
        <tr bgcolor="#ff9966">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><b>Итого</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	            {foreach CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part) as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{CStringUtils::numLocal(number_format($value,1,',',''))}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
			<td><b>{CStringUtils::numLocal(number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part),1,',',''))}</b></td>
			<td>&nbsp;</td>
		</tr>
    </table>
</form>
<form action="index.php" method="post" id="{$loadsId}">
	{CHtml::hiddenField("action", "saveAll")}
	{CHtml::hiddenField("kadri_id", $lecturer->getId())}
	{CHtml::hiddenField("year_id", $year->getId())}
	{CHtml::hiddenField("isBudget", $isBudget)}
	{CHtml::hiddenField("isContract", $isContract)}
	
    <table class="table table-striped table-bordered table-hover table-condensed table-load" border="1">
        <tr>
            <th>&nbsp;</th>
            <th style="vertical-align:middle; text-align:center;">#</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("discipline_id", $studyLoads->getFirstItem(), false, false)}</th>
            <th style="vertical-align:bottom;"><div class="vert-text">Факультет</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("speciality_id", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("level_id", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("groups_count", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("students_count", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("load_type_id", $studyLoads->getFirstItem(), false, false)}</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("comment", $studyLoads->getFirstItem(), false, false)}</th>
            {foreach $studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
				{foreach $rows as $kindId=>$value}
					{if in_array($kindId, array(0))}
						<th style="vertical-align:bottom;"><div class="vert-text">{$value}</div></th>
					{/if}
                {/foreach}
            {/foreach}
        </tr>
        <tr>
	        {$ths = 11}
	        {for $i=1 to $ths + count($studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal())}
	            <th style="text-align:center; background-color: #E6E6FF;">{$i}</th>
	        {/for}
        </tr>
        {counter start=0 print=false}
        {foreach $studyLoads->getItems() as $studyLoad}
	        <tr>
	            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	            	<td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузку')) { location.href='?action=delete&id={$studyLoad->getId()}'; }; return false;"></a></td>
	            {/if}
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $studyLoad, false, true)}</td>
	            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	            	<td><a href="?action=edit&id={$studyLoad->getId()}" title="{", "|join:CStudyLoadService::getLecturersNameByDiscipline($studyLoad->discipline)->getItems()}">{$studyLoad->discipline->getValue()}</a></td>
	            {else}
	            	<td>{$studyLoad->discipline->getValue()}</td>
	            {/if}
	            <td>ИРТ</td>
	            <td>{$studyLoad->direction->getValue()}</td>
	            <td>{$studyLoad->studyLevel->name}</td>
	            <td>{$studyLoad->groups_count}</td>
	            <td>{$studyLoad->students_count + $studyLoad->students_contract_count}</td>
	            <td>{$studyLoad->studyLoadType->name}</td>
	            <td>{$studyLoad->comment}</td>
	            {foreach $studyLoad->getStudyLoadTable()->getTableByKind($isBudget, $isContract) as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td>{CHtml::textField($studyLoad->getStudyLoadTable()->getFieldNameAllLoads($studyLoad->getId(), $typeId, $kindId), $value, "", "input-load")}</td>
						{/if}
	                {/foreach}
	            {/foreach}
	        </tr>
        {/foreach}
    </table>
    
	<div class="control-group">
        <div class="controls">
            {CHtml::submit("Сохранить")}
        </div>
    </div>
</form>
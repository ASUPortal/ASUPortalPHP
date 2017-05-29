<form action="index.php" method="post" id="loadsFall">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("discipline_id", $studyLoads->getFirstItem())}</th>
            <th>Факультет</th>
            <th>{CHtml::tableOrder("speciality_id", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("level_id", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("groups_count", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("students_count", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("load_type_id", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $studyLoads->getFirstItem())}</th>
	            {foreach $studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if in_array($kindId, array(0))}
							<th>{$value}</th>
						{/if}
	                {/foreach}
	            {/foreach}
            <th>Всего</th>
            <th>{CHtml::tableOrder("on_filial", $studyLoads->getFirstItem())}</th>
        </tr>
        {counter start=0 print=false}
        {foreach $studyLoads->getItems() as $studyLoad}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузку')) { location.href='?action=delete&id={$studyLoad->getId()}'; }; return false;"></a></td>
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $studyLoad, false, true)}</td>
	            <td><a href="?action=edit&id={$studyLoad->getId()}">{$studyLoad->discipline->getValue()}</a></td>
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
								<td>{$value}</td>
							{/if}
		                {/foreach}
		            {/foreach}
	            <td>{$studyLoad->getSumWorksValue()}</td>
	            <td>{$studyLoad->on_filial}</td>
	        </tr>
        {/foreach}
    </table>
</form>
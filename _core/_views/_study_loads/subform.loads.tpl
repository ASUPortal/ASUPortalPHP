<style>
	.vert-text {
		-ms-transform: rotate(90deg);
		-moz-transform: rotate(90deg);
		-webkit-transform: rotate(90deg);
		transform: rotate(90deg);
		
		-ms-transform-origin: left top 0;
		-moz-transform-origin: left top 0;
		-webkit-transform-origin: left top 0;
		transform-origin: left top 0;
		
		margin-left: 30px;
		padding: 0;
		float: left;
		height: 350px;
		width: 5px;
		white-space: nowrap;
	}
</style>

<form action="index.php" method="post" id="loadsFall">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("discipline_id", $studyLoads->getFirstItem())}</th>
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
<form action="index.php" method="post" id="loadsFall">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th>{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th>{CHtml::tableOrder("subject_id", $studyLoads->getFirstItem())}</th>
            <th>Факультет</th>
            <th>{CHtml::tableOrder("spec_id", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("level_id", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("groups_cnt", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("stud_cnt", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("hours_kind_type", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("lects", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("practs", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("labor", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("rgr", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("ksr", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("consult", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("test", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("exams", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("study_pract", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("work_pract", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kurs_proj", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("consult_dipl", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("gek", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("aspirants", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("aspir_manage", $studyLoads->getFirstItem())}</th>
            <th>{CHtml::tableOrder("duty", $studyLoads->getFirstItem())}</th>
            <th>Всего</th>
            <th>{CHtml::tableOrder("on_filial", $studyLoads->getFirstItem())}</th>
        </tr>
        {counter start=0 print=false}
        {foreach $studyLoads as $studyLoad}
	        <tr>
	            <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить нагрузку')) { location.href='?action=delete&id={$studyLoad->getId()}'; }; return false;"></a></td>
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $studyLoad, false, true)}</td>
	            <td><a href="?action=edit&id={$studyLoad->getId()}">{$studyLoad->discipline->getValue()}</a></td>
	            <td>ИРТ</td>
	            <td>{$studyLoad->direction->getValue()}</td>
	            <td>{$studyLoad->studyLevel->name}</td>
	            <td>{$studyLoad->groups_cnt}</td>
	            <td>{$studyLoad->stud_cnt}</td>
	            <td>{$studyLoad->studyLoadType->name}</td>
	            <td>{$studyLoad->comment}</td>
	            <td>{$studyLoad->lects + $studyLoad->lects_add}</td>
	            <td>{$studyLoad->practs + $studyLoad->practs_add}</td>
	            <td>{$studyLoad->labor + $studyLoad->labor_add}</td>
	            <td>{$studyLoad->rgr + $studyLoad->rgr_add}</td>
	            <td>{$studyLoad->ksr + $studyLoad->ksr_add}</td>
	            <td>{$studyLoad->consult + $studyLoad->consult_add}</td>
	            <td>{$studyLoad->test + $studyLoad->test_add}</td>
	            <td>{$studyLoad->exams + $studyLoad->exams_add}</td>
	            <td>{$studyLoad->study_pract + $studyLoad->study_pract_add}</td>
	            <td>{$studyLoad->work_pract + $studyLoad->work_pract_add}</td>
	            <td>{$studyLoad->kurs_proj + $studyLoad->kurs_proj_add}</td>
	            <td>{$studyLoad->consult_dipl + $studyLoad->consult_dipl_add}</td>
	            <td>{$studyLoad->gek + $studyLoad->gek_add}</td>
	            <td>{$studyLoad->aspirants + $studyLoad->aspirants_add}</td>
	            <td>{$studyLoad->aspir_manage + $studyLoad->aspir_manage_add}</td>
	            <td>{$studyLoad->duty + $studyLoad->duty_add}</td>
	            <td>{$studyLoad->sum}</td>
	            <td>{$studyLoad->on_filial}</td>
	        </tr>
        {/foreach}
    </table>
</form>
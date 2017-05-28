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
	            <td>{$studyLoad->groups_count}</td>
	            <td>{$studyLoad->students_count}</td>
	            <td>{$studyLoad->studyLoadType->name}</td>
	            <td>{$studyLoad->comment}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_LECTURE)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_PRACTICE)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_LAB_WORK)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_RGR)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_KSR)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_CONSULTATION)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_CREDIT)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_EXAMEN)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_STUDY_PRACTICE)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_WORK_PRACTICE)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_COURSE_PROJECT)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_DIPLOM_CONSULTATION)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_GEK)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_ASPIRANTS)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_ASPIRANTS_MANAGEMENT)}</td>
	            <td>{$studyLoad->getWorksValueByType(CStudyLoadWorkTypeIDConstants::LABOR_ATTENDANCE)}</td>
	            <td>{$studyLoad->getSumWorksValue()}</td>
	            <td>{$studyLoad->on_filial}</td>
	        </tr>
        {/foreach}
    </table>
</form>
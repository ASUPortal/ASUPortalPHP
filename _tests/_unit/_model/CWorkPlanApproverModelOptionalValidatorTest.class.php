<?php

Use PHPUnit\Framework\TestCase;

require_once("core_classloader.php");

$config = array(
    "components" => array(
        "cache" => array(
            "class" => "CCacheDummy"
        )
    )
);
CApp::createApplication($config);
CApp::getApp()->cache->set('application_settings_SYSTEM_LANGUAGE_DEFAULT', new CSetting(new CActiveRecord(array(
    'value' => 'ru'
))));

final class CWorkPlanApproverModelOptionalValidatorTest extends TestCase {
    
    public function testEmptyModelValidator() {
        // проверим, что объект CWorkPlanApproverModelOptionalValidator с минимумом необходимых данных не возвращает ошибок
        $model = new CModel();
        $model->corriculumDiscipline = new CModel();
        $validator = new CWorkPlanApproverModelOptionalValidator();
        $validator->onRead($model);
        $this->assertEmpty($validator->getError());
    }
    
    public function testNotEmptyModelValidator() {
        // проверим, что объект CWorkPlanApproverModelOptionalValidator с необходимыми данными не пустой
        
        // нагрузка рабочей программы
        $labor = new CActiveRecord(array(
            "value" => 10,
            "id" => 1
        ));
        $labors[] = new CCorriculumDisciplineLabor($labor);
    	
        // дисциплина рабочей программы
        $discipline = new CActiveRecord(array(
            "labors" => new CArrayList($labors),
            "sections" => new CArrayList(),
            "id" => 1
        ));
        $corriculumDiscipline = new CCorriculumDiscipline($discipline);
    	 
        // рабочая программа
        $workPlan = new CActiveRecord(array(
            "corriculumDiscipline" => $corriculumDiscipline,
            "terms" => new CArrayList(),
            "categories" => new CArrayList(),
            "id" => 1
        ));
        $model = new CWorkPlan($workPlan);
    	
        $validator = new CWorkPlanApproverModelOptionalValidator();
        $validator->onRead($model);
        $this->assertNotEmpty($validator->getError());
    }
}

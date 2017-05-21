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
    
    public function testModelValidator() {
        // проверим, что объект CWorkPlanApproverModelOptionalValidator с минимумом необходимых данных не возвращает ошибок
        $model = new CWorkPlan();
        $model->corriculum_discipline_id = 10;
        $validator = new CWorkPlanApproverModelOptionalValidator();
        $validator->onRead($model);
        $this->assertEmpty($validator->getError());
    }
}

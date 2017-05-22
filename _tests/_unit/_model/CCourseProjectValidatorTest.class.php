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

final class CCourseProjectValidatorTest extends TestCase {
    
    public function testModelValidator() {
        // проверим, что объект CCourseProjectValidator с минимумом необходимых данных не возвращает ошибок
        $model = new CModel();
        $model->group = new CModel();
        $model->group->name = "Name";
        $model->group->corriculum = new CModel();
        $validator = new CCourseProjectValidator();
        $validator->onRead($model);
        $validator->getError();
    }
}
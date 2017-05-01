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

    public function testEmptyModelValidations() {
        // проверим, что валидация пустой модели не возвращает ошибок
        $model = new CCourseProject();
        $validator = new CCourseProjectValidator();
        $validator->onRead($model);
        $validator->validate();
    }
}
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

final class ModelTest extends TestCase {
    private static $_excludes = array(
        'CPrintClassFieldToFieldAdapter',
        'CLecturerOuter',
        'CPerson'
    );

    public function testModelsInstantiation() {
        // загрузим все модельные классы, проверим, что можно создать их экземпляр
        $classes = CUtils::getAllClassesWithInterface('CActiveModel', '', self::$_excludes);
        foreach ($classes as $instance) {
                echo 'Checking class ' . get_class($instance) . "\n\r";
        }
    }

    public function testModelsRelations() {
        // установим объект для текущего сотрудника
        $person = new CActiveRecord(array(
            "id" => 1
        ));
        $model = new CPerson($person);
        CSession::setCurrentPerson($model);
    	
        // загрузим все модельные классы, проверим, что у них связи корректные
        $classes = CUtils::getAllClassesWithInterface('CActiveModel', '', self::$_excludes);
        foreach ($classes as $instance) {
                echo 'Checking class ' . get_class($instance) . "\n\r";
                //
                $r = new ReflectionMethod(get_class($instance), 'relations');
                $r->setAccessible(true);
                $relations = $r->invoke($instance);
                //
                foreach ($relations as $relation) {
                    if (array_key_exists('managerClass', $relation)) {
                        // попробуем создать класс менеджера
                        echo 'Manager ' . $relation['managerClass'] . "\n\r";
                        new $relation['managerClass']();
                    }
                    if (array_key_exists('targetClass', $relation)) {
                        // попробуем создать экземпляр целевого класса
                        echo 'Target class ' . $relation['targetClass'] . "\n\r";
                        new $relation['targetClass']();
                    }
                }
        }
    }
}
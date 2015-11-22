<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 07.09.15
 * Time: 21:11
 *
 * @property int section_id
 * @property int term_id
 * @property CWorkPlanContentSection section
 * @property CWorkPlanTerm term
 * @property CTerm loadType
 * @property CArrayList topics
 * @property CArrayList technologies
 * @property CArrayList selfEducations
 */
class CWorkPlanContentSectionLoad extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_LOADS;

    protected function relations() {
        return array(
            "section" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_section",
                "storageField" => "section_id",
                "targetClass" => "CWorkPlanContentSection"
            ),
            "term" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_term",
                "storageField" => "term_id",
                "targetClass" => "CWorkPlanTerm"
            ),
            "loadType" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_loadType",
                "storageField" => "load_type_id",
                "targetClass" => "CTerm"
            ),
            "topics" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_CONTENT_TOPICS,
                "storageCondition" => "load_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSectionLoadTopic",
                "managerOrder" => "_deleted asc, `ordering` asc"
            ),
            "technologies" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_CONTENT_TECHNOLOGIES,
                "storageCondition" => "load_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSectionLoadTechnology",
                "managerOrder" => "_deleted asc, `ordering` asc"
            ),
            "selfEducations" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_SELFEDUCATION,
                "storageCondition" => "load_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanSelfEducationBlock",
                "managerOrder" => "_deleted asc, `ordering` asc"
            )
        );
    }


    public function attributeLabels() {
        return array(
            "load_type_id" => "Вид нагрузки",
            "term_id" => "Семестр",
            "value" => "Число часов",
            "ordering" => "Порядковый номер"
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "value"
            ),
            "selected" => array(
                "term_id",
                "load_type_id"
            )
        );
    }


}
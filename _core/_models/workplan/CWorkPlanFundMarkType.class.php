<?php
/**
 * 
 * @property int plan_id
 * @property int section_id
 *
 * @property CArrayList competentions
 * @property CArrayList levels
 * @property CArrayList controls
 */
class CWorkPlanFundMarkType extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_FUND_MARK_TYPES;

    protected function relations() {
        return array(
            "section" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_section",
                "storageField" => "section_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getWorkPlanContentSection"
            ),
            "competentions" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_FUND_MARK_TYPES_COMPETENTIONS,
                "leftCondition" => "fund_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "competention_id",
                "targetClass" => "CTerm"
            ),
        	"levels" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_FUND_MARK_TYPES_LEVELS,
                "leftCondition" => "fund_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "level_id",
                "targetClass" => "CTerm"
            ),
        	"controls" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "joinTable" => TABLE_WORK_PLAN_FUND_MARK_TYPES_CONTROLS,
                "leftCondition" => "fund_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "control_id",
                "targetClass" => "CTerm"
            )
        );
    }

    public function attributeLabels() {
        return array(
            "section_id" => "Контролируемый раздел (тема) дисциплины",
            "competentions" => "Контролируемая компетенция",
            "levels" => "Уровень освоения, определяемый этапом формирования компетенции",
            "controls" => "Наименование оценочного средства"
        );
    }

    protected function validationRules() {
        return array(
            "selected" => array(
                "section_id"
            )
        );
    }

}
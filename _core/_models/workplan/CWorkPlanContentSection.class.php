<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 02.04.15
 * Time: 17:35
 *
 * @property int sectionIndex
 * @property string name
 * @property CArrayList controls
 * @property int category_id
 * @property CWorkPlan plan
 * @property CWorkPlanContentCategory category
 * @property CArrayList loads
 * @property CArrayList controlTypes
 * @property CArrayList recommendedLiterature
 */
class CWorkPlanContentSection extends CActiveModel{
    protected $_table = TABLE_WORK_PLAN_CONTENT_SECTIONS;
    protected $_controls;
    protected $_recommendedLiterature;

    protected function relations() {
        return array(
            "controls" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_controls",
                "joinTable" => TABLE_WORK_PLAN_CONTENT_CONTROLS,
                "leftCondition" => "section_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "control_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getTerm"
            ),
            "category" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_category",
                "storageField" => "category_id",
                "managerClass" => "CBaseManager",
                "managerGetObject" => "getWorkPlanContentCategory"
            ),
            "loads" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageTable" => TABLE_WORK_PLAN_CONTENT_LOADS,
                "storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()),
                "targetClass" => "CWorkPlanContentSectionLoad",
                "managerOrder" => "`_deleted` asc, `ordering` asc"
            ),
        	"loadsDisplay" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_CONTENT_LOADS,
        		"storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanContentSectionLoad",
        		"managerOrder" => "`_deleted` asc, `ordering` asc"
        	),
        	"controlTypes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_TYPES_CONTROL,
        		"storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanControlTypes",
                "managerOrder" => "`ordering` asc"
        	),
        	"fundMarkTypes" => array(
        		"relationPower" => RELATION_HAS_MANY,
        		"storageTable" => TABLE_WORK_PLAN_FUND_MARK_TYPES,
        		"storageCondition" => "section_id = " . (is_null($this->getId()) ? 0 : $this->getId()) . " and _deleted=0",
        		"targetClass" => "CWorkPlanFundMarkType",
                "managerOrder" => "`ordering` asc"
        	),
            "recommendedLiterature" => array(
            	"relationPower" => RELATION_MANY_TO_MANY,
            	"storageProperty" => "_recommendedLiterature",
            	"joinTable" => TABLE_WORK_PLAN_RECOMMENDED_LITERATURE,
            	"leftCondition" => "section_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
            	"rightKey" => "literature_id",
            	"managerClass" => "CBaseManager",
            	"managerGetObject" => "getWorkPlanLiterature"
            )
        );
    }

    protected function validationRules() {
        return array(
            "required" => array(
                "name",
                "sectionIndex",
                "controls",
                "category_id"
            )
        );
    }
    
    protected function modelValidators() {
    	return array(
    		new CWorkPlanContentLoadModelOptionalValidator()
    	);
    	 
    }

    public function attributeLabels() {
        return array(
            "name" => "Название раздела",
            "sectionIndex" => "Номер раздела",
            "category_id" => "Категория",
            "controls" => "Формы текущего контроля",
            "content" => "Содержание раздела",
            "recommendedLiterature" => "Литература, рекомендуемая студентам"
        );
    }


}
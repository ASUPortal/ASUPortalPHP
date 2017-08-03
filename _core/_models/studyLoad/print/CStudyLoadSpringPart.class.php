<?php

class CStudyLoadSpringPart extends CStudyLoadFallPart {
    public function getFieldName()
    {
        return "Значения по учебной нагрузке за весенний семестр";
    }
    
    /**
     * Весенний семестр из учебной нагрузки
     *
     * @return CYearPart
     */
    public function getYearPart()
    {
    	return CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING);
    }
}
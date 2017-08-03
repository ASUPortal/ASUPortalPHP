<?php

class CStudyLoadSpringPartTotal extends CStudyLoadFallPartTotal {
    public function getFieldName()
    {
        return "Итого по учебной нагрузке за весенний семестр";
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
<?php

class CStudyLoadSpringPart extends CStudyLoadFallPart {
    public function getFieldName()
    {
        return "Значения по учебной нагрузке за весенний семестр";
    }
    
    public function getYearPart()
    {
    	return CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING);
    }
}
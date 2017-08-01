<?php

class CStudyLoadSpringPartTotal extends CStudyLoadFallPartTotal {
    public function getFieldName()
    {
        return "Итого по учебной нагрузке за весенний семестр";
    }
    
    public function getYearPart()
    {
    	return CStudyLoadService::getYearPartByAlias(CStudyLoadYearPartsConstants::SPRING);
    }
}
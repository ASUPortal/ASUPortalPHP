<?php
/**
 * Класс учебной нагрузки без поддержки версионирования
 * (для смены признака редактируемости без пересохранения)
 */
class CStudyLoadWithOutVersionControl extends CActiveModel {
    protected $_table = TABLE_WORKLOAD;
    
}
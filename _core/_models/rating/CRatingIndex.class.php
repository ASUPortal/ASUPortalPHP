<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 24.09.12
 * Time: 21:31
 * To change this template use File | Settings | File Templates.
 */
class CRatingIndex extends CActiveModel {
    protected $_table = TABLE_RATING_INDEXES;
    protected $_year = null;
    private $_indexValues = null;
    protected $_savedIndexValues = null;
    /**
     * @static
     * @return string
     */
    public static function getClassName() {
        return __CLASS__;
    }
    protected function relations() {
        return array(
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            ),
            "savedIndexValues" => array(
                "relationPower" => RELATION_HAS_MANY,
                "storageProperty" => "_savedIndexValues",
                "storageTable" => TABLE_RATING_INDEX_VALUES,
                "storageCondition" => "index_id = " . $this->id,
                "managerClass" => "CRatingManager",
                "managerGetObject" => "getRatingIndexValue"
            )
        );
    }
    protected function validationRules() {
        return array(
            "required" => array(
                "title",
                "year_id"
            )
        );
    }
    public function attributeLabels() {
        return array(
            "title" => "Название показателя",
            "year_id" => "Год",
        );
    }
    /**
     * Отбрасываем все связанные значения. Нужно для нормальный работы
     * с Person-ом.
     */
    public function truncateValues() {
        $this->_indexValues = null;
    }
    /**
     * Добавление к показателю значения в список
     *
     * @param CRatingIndexValue $value
     */
    public function addIndexValue(CRatingIndexValue $value) {
        if (is_null($this->_indexValues)) {
            $this->_indexValues = new CArrayList();
        }
        $this->_indexValues->add($value->id, $value);
    }
    /**
     * Значение показателя на основе добавленных в него значений
     *
     * @return int
     */
    public function getIndexValue() {
        $res = 0;
        foreach ($this->getIndexValues()->getItems() as $value) {
            $res += $value->getValue();
        }
        return $res;
    }
    /**
     * Возможноные значения показателя
     *
     * @return CArrayList
     */
    public function getIndexValues() {
        if (is_null($this->_indexValues)) {
            $this->_indexValues = new CArrayList();
            // идем в словарь, по которому выбираются значения
            $manager = $this->manager_class;
            $method = $this->manager_method;
            $fromTaxonomy = new CArrayList();
            if ($manager != "" && $method != "") {
                $str = '$fromTaxonomy = '.$manager.'::'.$method.';';
                eval($str);
            }
            // ходим по значениям, полученным из словаря, преобразуем их
            // в значения показателя. попутно смотрим, вдруг для них уже
            // определен в сохраненных вес
            $resArray = new CArrayList();
            foreach ($fromTaxonomy->getItems() as $term) {
                if (!$this->hasSavedValueFromTaxonomy($term->id)) {
                    $value = new CRatingIndexValue();
                    $value->manager_class = $manager;
                    $value->manager_method = $method;
                    $value->fromTaxonomy = "1";
                    $value->title = $term->id;
                    $value->index_id = $this->id;
                    $resArray->add("unsaved_".$value->title, $value);
                } else {
                    $value = $this->getSavedValueFromTaxonomy($term->id);
                    if (!is_null($value)) {
                        $resArray->add($value->getId(), $value);
                    }
                }
            }
            // добавляем вручную созданные и сохраненны (если их еще нет)
            foreach ($this->savedIndexValues->getItems() as $item) {
                $resArray->add($item->getId(), $item);
            }
            // возврат значения =)
            $this->_indexValues = $resArray;
        }
        return $this->_indexValues;
    }
    /**
     * Значения показателей, в которых установлены значения
     * (доступные для выбора пользователю)
     *
     * @return CArrayList
     */
    public function getAvailableIndexValues() {
        $res = new CArrayList();
        foreach ($this->getIndexValues()->getItems() as $item) {
            if ($item->getValue() != "") {
                $res->add($item->getId(), $item);
            }
        }
        return $res;
    }
    /**
     * Значения показателей для подстановки в списки
     *
     * @return array
     */
    public function getAvailableIndexValuesList() {
        $res = array();
        foreach ($this->getAvailableIndexValues()->getItems() as $item) {
            $res[$item->getId()] = $item->getTitle();
        }
        return $res;
    }
    /**
     * @param $key
     * @return CRatingIndexValue
     */
    private function getSavedValueFromTaxonomy($key) {
        $res = null;
        foreach ($this->getSavedValuesFromTaxonomy()->getItems() as $item) {
            if ($item->title == $key) {
                $res = $item;
            }
        }
        return $res;
    }
    /**
     * Есть ли в сохраненных значениях значение с указанным ключом
     *
     * @param $key
     * @return bool
     */
    private function hasSavedValueFromTaxonomy($key) {
        $res = false;
        foreach ($this->getSavedValuesFromTaxonomy()->getItems() as $item) {
            if ($item->title == $key) {
                $res = true;
            }
        }
        return $res;
    }
    /**
     * Сохраненные значения показателей из словарей с указанным значением
     *
     * @return CArrayList
     */
    private function getSavedValuesFromTaxonomy() {
        $res = new CArrayList();
        foreach ($this->savedIndexValues->getItems() as $item) {
            if ($item->fromTaxonomy == "1") {
                $res->add($item->getId(), $item);
            }
        }
        return $res;
    }
    /**
     * Можно ли выбирать несколько значений показателя
     *
     * @return bool
     */
    public function isMultivalue() {
        return ($this->isMultivalue == "1");
    }
    /**
     * Суммарное значение показателя по всем людям
     *
     * @return int
     */
    public function getTotalValue() {
        $res = 0;
        foreach ($this->getAvailableIndexValues()->getItems() as $value) {
            $res += $value->getValue() * $value->persons->getCount();
        }
        return $res;
    }
    public function getMaxValue() {
        $res = -100;
        foreach ($this->getAvailableIndexValues()->getItems() as $value) {
            foreach ($value->persons->getItems() as $person) {
                $a = $person->getRatingIndexesByYear($this->year)->getItem($this->id)->getIndexValue();
                if ($a > $res) {
                    $res = $a;
                }
            }
        }
        return $res;
    }
    public function getMinValue() {
        $res = 100;
        foreach ($this->getAvailableIndexValues()->getItems() as $value) {
            foreach ($value->persons->getItems() as $person) {
                $a = $person->getRatingIndexesByYear($this->year)->getItem($this->id)->getIndexValue();
                if ($a < $res) {
                    $res = $a;
                }
            }
        }
        return $res;
    }
    public function getAverageValue() {
        $res = array();
        foreach ($this->getAvailableIndexValues()->getItems() as $value) {
            foreach ($value->persons->getItems() as $person) {
                if (!array_key_exists($person->getId(), $res)) {
                    $a = $person->getRatingIndexesByYear($this->year)->getItem($this->getId())->getIndexValue();
                    $res[$person->getId()] = $a;
                }
            }
        }
        if (count($res) == 0) {
            return 0;
        }
        return $this->getTotalValue()/count($res);
    }
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 11:46
 * Объект-критерий поиска
 */
class C2DbCriteria {
    const PARAM_PREFIX = ":asu";
    /**
     * Считаем количество добавленных параметров
     *
     * @var int
     */
    public static $paramCount=0;
    /**
     * Массив параметров, которые будут заменены в запросе
     *
     * @var array
     */
    public $params = array();
    /**
     * Извлекаемые поля
     *
     * @var string
     */
    public $select = "*";
    /**
     * Отбор только уникальных записей
     *
     * @var bool
     */
    public $distinct = false;
    /**
     * Условие отбора записей
     *
     * @var string
     */
    public $condition = "";
    /**
     * Количество извлекаемых записей. Если меньше нуля, то извлекаются
     * все записи
     *
     * @var int
     */
    public $limit = -1;
    /**
     * Начиная с какой записи извлекать данные. Если меньше нуля, то с первой
     *
     * @var int
     */
    public $offset = -1;
    /**
     * Порядок сортировки извлекаемых записей
     *
     * @var string
     */
    public $order = "";
    /**
     * Связь с другими таблицами
     *
     * @var string
     */
    public $join = "";
    /**
     * Используется для загрузки связанных объектов и для горячей дозагрузки
     * при выполнении запроса
     *
     * @var mixed
     */
    public $with;
    /**
     * Псевдоним таблицы
     *
     * @var string
     */
    public $alias;
    /**
     * Должны ли связанные таблицы быть объединены в один запрос при извлечении данных
     *
     * @var boolean
     */
    public $together;

    /**
     * Конструктор. Можно передавать параметры для инициализации
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        foreach ($data as $key=>$value) {
            $this->$key = $value;
        }
    }

    /**
     * Добавление условия отбора. По умолчанию объединяется через AND
     *
     * @param $condition
     * @param string $operator
     * @return C2DbCriteria
     */
    public function addCondition($condition, $operator = "AND") {
        if (is_array($condition)) {
            if ($condition === array()) {
                return $this;
            }
            $condition='('.implode(') '.$operator.' (',$condition).')';
        }
        if ($this->condition === "") {
            $this->condition = $condition;
        } else {
            $this->condition='('.$this->condition.') '.$operator.' ('.$condition.')';
        }
        return $this;
    }

    /**
     * Добавление условия для поиска по вхождению.
     *
     * @param $column - столбец, в котором ищем
     * @param $keyword - ключевое слово
     * @param bool $escape - нужно ли исключать из ключевого слова специальные символы, добавлять %
     * с обеих сторон
     * @param string $operator - каким оператором объединять поиск по ключевому слову с остальными запросами
     * @param string $like - вид лайка - LIKE/NOT LIKE
     * @return C2DbCriteria
     */
    public function addSearchCondition($column, $keyword, $escape=true, $operator='AND', $like='LIKE') {
        if ($keyword == "") {
            return $this;
        }
        if ($escape) {
            $keyword = '%'.strtr($keyword,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
        }
        $condition = $column." $like ".self::PARAM_PREFIX.self::$paramCount;
        $this->params[self::PARAM_PREFIX.self::$paramCount++] = $keyword;
        return $this->addCondition($condition, $operator);
    }

    /**
     * Добавление критериев для поиска по совпадению значения какого-либо столбца.
     *
     * @param $columns - имя_столбца->значение
     * @param string $columnOperator - условие объединения условий из столбцов
     * @param string $operator - способ добавления этого условия к остальным
     * @return C2DbCriteria
     */
    public function addColumnCondition($columns, $columnOperator='AND', $operator='AND') {
        $params = array();
        foreach ($columns as $name=>$value) {
            if ($value === null) {
                $params[] = $name." IS NULL";
            } else {
                $params[] = $name.'='.self::PARAM_PREFIX.self::$paramCount;
                $this->params[self::PARAM_PREFIX.self::$paramCount++] = $value;
            }
        }
        return $this->addCondition(implode(" $columnOperator ",$params), $operator);
    }

    /**
     * Конвертируем объект в массив
     *
     * @return array
     */
    public function toArray() {
        $result = array();
        foreach(array('select', 'condition', 'params', 'limit', 'offset', 'order', 'group', 'join', 'distinct', 'with', 'alias', 'together') as $name)
            $result[$name]=$this->$name;
        return $result;
    }
}

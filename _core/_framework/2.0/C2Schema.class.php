<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 12:30
 * Метаданные соединения с базой данных MySQL
 */
class C2Schema {
    private $_tableNames=array();
    private $_tables=array();
    private $_connection;
    private $_builder;
    private $_cacheExclude=array();
    private $_mysqli = null;
    /**
     * Стандартные типы столбцов в БД
     *
     * @var array
     */
    public $columnTypes=array(
        'pk' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
        'string' => 'varchar(255)',
        'text' => 'text',
        'integer' => 'int(11)',
        'float' => 'float',
        'decimal' => 'decimal',
        'datetime' => 'datetime',
        'timestamp' => 'timestamp',
        'time' => 'time',
        'date' => 'date',
        'binary' => 'blob',
        'boolean' => 'tinyint(1)',
        'money' => 'decimal(19,4)',
    );

    /**
     * @param C2DbConnection $connection
     */
    public function __connect(C2DbConnection $connection) {
        $this->_connection = $connection;
    }
    /**
     * Соединение с базой данных
     *
     * @return C2DbConnection
     */
    public function getDbConnection() {
        return $this->_connection;
    }

    /**
     * Построитель запросов
     *
     * @return C2CommandBuilder
     */
    public function getCommandBuilder() {
        if (is_null($this->_builder)) {
            $this->_builder = new C2CommandBuilder($this);
        }
        return $this->_builder;
    }

    /**
     * Загрузка метаданных о таблице
     *
     * @param $name
     * @return C2TableSchema|null
     */
    protected function loadTable($name) {
        $table = new C2TableSchema;
        $this->resolveTableNames($table,$name);
        if($this->findColumns($table)) {
            $this->findConstraints($table);
            return $table;
        } else {
            return null;
        }
    }

    /**
     * Получить имена таблицы во всех форматах
     * Заполнение метаданных
     *
     * @param C2TableSchema $table
     * @param $name
     */
    protected function resolveTableNames(C2TableSchema $table, $name) {
        $parts = explode('.',str_replace(array('`','"'),'',$name));
        if(isset($parts[1])) {
            $table->schemaName = $parts[0];
            $table->name = $parts[1];
            $table->rawName = $this->quoteTableName($table->schemaName).'.'.$this->quoteTableName($table->name);
        } else {
            $table->name=$parts[0];
            $table->rawName=$this->quoteTableName($table->name);
        }
    }

    /**
     * Заключить в кавычки имя таблицы
     *
     * @param $name
     * @return string
     */
    public function quoteTableName($name) {
        if(strpos($name,'.') === false) {
            return $this->quoteSimpleTableName($name);
        }
        $parts = explode('.',$name);
        foreach($parts as $i=>$part) {
            $parts[$i] = $this->quoteSimpleTableName($part);
        }
        return implode('.',$parts);
    }

    /**
     * Просто закллючить в кавычки.
     *
     * @param $name
     * @return string
     */
    public function quoteSimpleTableName($name) {
        return "'".$name."'";
    }

    /**
     * Создать объекты-метаданных о столбцах
     *
     * @param $table
     * @return bool
     */
    protected function findColumns($table) {
        $sql='SHOW FULL COLUMNS FROM '.$table->rawName;
        try
        {
            $columns=$this->getDbConnection()->createCommand($sql)->queryAll();
        }
        catch(Exception $e)
        {
            return false;
        }
        foreach($columns as $column)
        {
            $c=$this->createColumn($column);
            $table->columns[$c->name]=$c;
            if($c->isPrimaryKey)
            {
                if($table->primaryKey===null)
                    $table->primaryKey=$c->name;
                elseif(is_string($table->primaryKey))
                    $table->primaryKey=array($table->primaryKey,$c->name);
                else
                    $table->primaryKey[]=$c->name;
                if($c->autoIncrement)
                    $table->sequenceName='';
            }
        }
        return true;
    }
    /**
     * Quotes a column name for use in a query.
     * If the column name contains prefix, the prefix will also be properly quoted.
     * @param string $name column name
     * @return string the properly quoted column name
     * @see quoteSimpleColumnName
     */
    public function quoteColumnName($name)
    {
        if(($pos=strrpos($name,'.'))!==false)
        {
            $prefix=$this->quoteTableName(substr($name,0,$pos)).'.';
            $name=substr($name,$pos+1);
        }
        else
            $prefix='';
        return $prefix . ($name==='*' ? $name : $this->quoteSimpleColumnName($name));
    }
    /**
     * Creates a table column.
     * @param array $column column metadata
     * @return CDbColumnSchema normalized column metadata
     */
    protected function createColumn($column)
    {
        $c=new C2ColumnSchema;
        $c->name=$column['Field'];
        $c->rawName=$this->quoteColumnName($c->name);
        $c->allowNull=$column['Null']==='YES';
        $c->isPrimaryKey=strpos($column['Key'],'PRI')!==false;
        $c->isForeignKey=false;
        $c->init($column['Type'],$column['Default']);
        $c->autoIncrement=strpos(strtolower($column['Extra']),'auto_increment')!==false;
        $c->comment=$column['Comment'];

        return $c;
    }
    /**
     * Collects the foreign key column details for the given table.
     * @param C2TableSchema $table the table metadata
     */
    protected function findConstraints($table)
    {
        $row=$this->getDbConnection()->createCommand('SHOW CREATE TABLE '.$table->rawName)->queryRow();
        $matches=array();
        $regexp='/FOREIGN KEY\s+\(([^\)]+)\)\s+REFERENCES\s+([^\(^\s]+)\s*\(([^\)]+)\)/mi';
        foreach($row as $sql)
        {
            if(preg_match_all($regexp,$sql,$matches,PREG_SET_ORDER))
                break;
        }
        foreach($matches as $match)
        {
            $keys=array_map('trim',explode(',',str_replace(array('`','"'),'',$match[1])));
            $fks=array_map('trim',explode(',',str_replace(array('`','"'),'',$match[3])));
            foreach($keys as $k=>$name)
            {
                $table->foreignKeys[$name]=array(str_replace(array('`','"'),'',$match[2]),$fks[$k]);
                if(isset($table->columns[$name]))
                    $table->columns[$name]->isForeignKey=true;
            }
        }
    }
}

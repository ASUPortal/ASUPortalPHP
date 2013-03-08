<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 03.02.13
 * Time: 17:28
 * To change this template use File | Settings | File Templates.
 */
class C2DbCommand {
    /**
     * @var array the parameters (name=>value) to be bound to the current query.
     * @since 1.1.6
     */
    public $params=array();

    private $_connection;
    private $_text;
    private $_statement;
    private $_paramLog=array();
    private $_query;
    private $_fetchMode = array(PDO::FETCH_ASSOC);

    /**
     * Constructor.
     * @param C2DbConnection $connection the database connection
     * @param mixed $query the DB query to be executed. This can be either
     * a string representing a SQL statement, or an array whose name-value pairs
     * will be used to set the corresponding properties of the created command object.
     *
     * For example, you can pass in either <code>'SELECT * FROM tbl_user'</code>
     * or <code>array('select'=>'*', 'from'=>'tbl_user')</code>. They are equivalent
     * in terms of the final query result.
     *
     * When passing the query as an array, the following properties are commonly set:
     * {@link select}, {@link distinct}, {@link from}, {@link where}, {@link join},
     * {@link group}, {@link having}, {@link order}, {@link limit}, {@link offset} and
     * {@link union}. Please refer to the setter of each of these properties for details
     * about valid property values. This feature has been available since version 1.1.6.
     *
     * Since 1.1.7 it is possible to use a specific mode of data fetching by setting
     * {@link setFetchMode FetchMode}. See {@link http://www.php.net/manual/en/function.PDOStatement-setFetchMode.php}
     * for more details.
     */
    public function __construct(C2DbConnection $connection,$query=null)
    {
        $this->_connection=$connection;
        if(is_array($query)) {
            foreach($query as $name=>$value) {
                $this->$name=$value;
            }
        } else {
            $this->setText($query);
        }
    }
    /**
     * @return string the SQL statement to be executed
     */
    public function getText()
    {
        if($this->_text=='' && !empty($this->_query))
            $this->setText($this->buildQuery($this->_query));
        return $this->_text;
    }

    /**
     * Specifies the SQL statement to be executed.
     * Any previous execution will be terminated or cancel.
     * @param string $value the SQL statement to be executed
     * @return C2DbCommand this command instance
     */
    public function setText($value)
    {
        if($this->_connection->tablePrefix!==null && $value!='')
            $this->_text=preg_replace('/{{(.*?)}}/',$this->_connection->tablePrefix.'\1',$value);
        else
            $this->_text=$value;
        $this->cancel();
        return $this;
    }
    /**
     * @return C2DbConnection the connection associated with this command
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @return PDOStatement the underlying PDOStatement for this command
     * It could be null if the statement is not prepared yet.
     */
    public function getPdoStatement()
    {
        return $this->_statement;
    }
    /**
     * Cancels the execution of the SQL statement.
     */
    public function cancel()
    {
        $this->_statement=null;
    }
    /**
     * Builds a SQL SELECT statement from the given query specification.
     * @param array $query the query specification in name-value pairs. The following
     * query options are supported: {@link select}, {@link distinct}, {@link from},
     * {@link where}, {@link join}, {@link group}, {@link having}, {@link order},
     * {@link limit}, {@link offset} and {@link union}.
     * @return string the SQL statement
     * @since 1.1.6
     */
    public function buildQuery($query)
    {
        $sql=!empty($query['distinct']) ? 'SELECT DISTINCT' : 'SELECT';
        $sql.=' '.(!empty($query['select']) ? $query['select'] : '*');

        if(!empty($query['from']))
            $sql.="\nFROM ".$query['from'];
        else
            throw new C2DbException(App::t('yii','The DB query must contain the "from" portion.'));

        if(!empty($query['join']))
            $sql.="\n".(is_array($query['join']) ? implode("\n",$query['join']) : $query['join']);

        if(!empty($query['where']))
            $sql.="\nWHERE ".$query['where'];

        if(!empty($query['group']))
            $sql.="\nGROUP BY ".$query['group'];

        if(!empty($query['having']))
            $sql.="\nHAVING ".$query['having'];

        if(!empty($query['union']))
            $sql.="\nUNION (\n".(is_array($query['union']) ? implode("\n) UNION (\n",$query['union']) : $query['union']) . ')';

        if(!empty($query['order']))
            $sql.="\nORDER BY ".$query['order'];

        $limit=isset($query['limit']) ? (int)$query['limit'] : -1;
        $offset=isset($query['offset']) ? (int)$query['offset'] : -1;
        if($limit>=0 || $offset>0)
            $sql=$this->_connection->getCommandBuilder()->applyLimit($sql,$limit,$offset);

        return $sql;
    }

}

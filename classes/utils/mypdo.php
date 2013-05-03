<?php
class MyPDO extends PDO
{
    protected $_table_prefix;
    protected $_table_suffix;

    public function __construct($dsn, $user = null, $password = null, $driver_options = array(), $prefix = null)
    {
        $this->_table_prefix = $prefix;        
        parent::__construct($dsn, $user, $password, $driver_options);
    }

    public function exec($statement)
    {
        $statement = $this->_tablePrefix($statement);
        return parent::exec($statement);
    }

    public function prepare($statement, $driver_options = array())
    {
        $statement = $this->_tablePrefix($statement);
        return parent::prepare($statement, $driver_options);
    }

    public function query($statement)
    {
        $statement = $this->_tablePrefix($statement);
        $args      = func_get_args();

        if (count($args) > 1) {
            return call_user_func_array(array($this, 'parent::query'), $args);
        } else {
            return parent::query($statement);
        }
    }

    protected function _tablePrefix($statement)
    {
        $sql_find = array(
                '~(FROM\s+`?)~',
                '~(INTO\s+`?)~',
                '~(JOIN\s+`?)~',
                '~(UPDATE\s+`?)~',
                '~(CREATE TABLE\s+`?)~'
        );

        $sql_replace = array(
                '$1'.$this->_table_prefix,
                '$1'.$this->_table_prefix,
                '$1'.$this->_table_prefix,
                '$1'.$this->_table_prefix,
                '$1'.$this->_table_prefix
        );        
        $res = preg_replace($sql_find, $sql_replace, $statement);     
        // echo $res."<br/>";
        return $res;
    }
}
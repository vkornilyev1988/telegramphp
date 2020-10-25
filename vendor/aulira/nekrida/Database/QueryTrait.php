<?php

namespace Nekrida\Database;

use Nekrida\Core\Database;

abstract class QueryTrait
{
    protected $table = '';

    protected $aliases = [];

    protected $wheres = [];

    protected $preparedStatement;

    protected $isPrepared;

    /** @var \PDO */
    protected $pdoObject;

    /** @var \PDOStatement */
    protected $pdoPrepared;

    public function __construct($table, $schema = 0) {
        $this->table = $table;
        $this->pdoObject = (is_string($schema) || $schema === 0) ? Database::getInstance($schema) : $schema;
    }

    /**
     * @param $table
     * @return $this
     * @deprecated use table($table) instead
     */
    public function from($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Set table for the query
     * @param $table
     * @return $this
     */
    public function table($table) {
        $this->table = $table;
        return $this;
    }

    protected function getDependentTable($table) {
        if (class_exists($table) && is_subclass_of($table,'Query'))
            $table = $table::TABLE_NAME;
        if ($this->table > $table)
            return $table.'_'.$this->table;
        else
            return $this->table.'_'.$table;
    }

    /**
     * Choose associative table based on current table.
     * @param $table
     * @return $this;
     */
    public function dependentTable($table) {
        $this->table = $this->getDependentTable($table);
        return $this;
    }

    /**
     * SQL Where clause. Multiple wheres form AND clause. Value is quoted
     * @param string $item column
     * @param string $sign
     * @param int|string|null $value
     * @return $this
     */
    public function where($item,$sign,$value) {
        $this->wheres[] = [$item,$sign,$value];
        return $this;
    }

    /**
     * SQL Where clause. Multiple wheres form AND clause.
     * @param string $item1 column
     * @param string $sign
     * @param string $item2
     * @return $this
     */
    public function whereA($item1, $sign, $item2) {
        $this->wheres[] = "$item1 $sign $item2";
        return $this;
    }

    /**
     * SQL Where clause. Enters the query as it is, without parsing.
     * @param string $where
     * @return $this
     */
    public function whereRaw($where) {
        $this->wheres[] = $where;
        return $this;
    }

    /**
     * Builds the query to be passed to the database
     * @param int $preparedItems How many items are to be prepared before executing
     * @return void
     */
    public abstract function build($preparedItems = 0);

    public function query($args = []) {
        if (is_null($this->preparedStatement) || $this->isPrepared != count($args)) $this->build(count($args));
        if (empty($args)) {
            $this->pdoPrepared = $this->pdoObject->query($this->preparedStatement);
        } else {
            $this->pdoPrepared = $this->pdoObject->prepare($this->preparedStatement);
            $this->pdoPrepared->execute($args);
        }
        return $this;
    }

    //PROTECTED FUNCTIONS

    protected function getTableAlias($table) {
        $name = '';
        foreach( explode('_',$table) as $item)
            $name .= strtolower($item[0]);
        if (in_array($name,$this->aliases)) {
            $i = 1;
            while (in_array($name.$i,$this->aliases))
                $i++;
            $name .=$i;
        }
        $this->aliases[] = $name;

        return $name;
    }

    protected function quote($value) {
        if (is_string($value)) {
            return "'".str_replace(["'",'\\'],["''","\\\\"],$value)."'";
        } elseif (is_null($value))
            return 'null';
        else {
            return $value;
        }
    }

    protected function quoteCount($value, &$counter, $count) {
        if (is_string($value)) {
            if (($value == '?' || strpos($value,':') === 0) && $counter < $count) {
                $counter++;
                return $value;
            } else
                return "'".str_replace(["'",'\\'],["''","\\\\"],$value)."'";
        } elseif (is_null($value))
            return 'null';
        else
            return $value;
    }

    protected function quoteIfArray($item, &$counter, $count) {
        if (is_array($item))
            return $item[0] . ' ' . $item[1] . ' ' . ($count ? $this->quoteCount($item[2], $counter, $count) : $this->quote($item[2]));
        else
            return $item;
    }

    //GETTERS AND SETTERS

    public function __toString()
    {
        if (is_null($this->preparedStatement)) $this->build();
        return $this->preparedStatement;
    }

    //PDO functions wrapper

    public function lastInsertId() {
        return $this->pdoObject->lastInsertId();
    }

    public function closeCursor () : bool { return $this->pdoPrepared->closeCursor(); }

    public function columnCount () : int { return $this->pdoPrepared->columnCount(); }

    public function debugDumpParams () { $this->pdoPrepared->debugDumpParams(); return $this; }

    public function errorCode () : string { return $this->pdoPrepared->errorCode(); }

    public function errorInfo () : array { return $this->pdoPrepared->errorInfo(); }

    public function fetch ($fetch_style = \PDO::FETCH_BOTH , $cursor_orientation = \PDO::FETCH_ORI_NEXT , $cursor_offset = 0 ) { return $this->pdoPrepared->fetch($fetch_style,$cursor_orientation,$cursor_offset);}

    public function fetchAll ($fetch_style = \PDO::FETCH_BOTH) { return $this->pdoPrepared->fetchAll($fetch_style); }

    public function fetchColumn ($column_number = 0) {return $this->pdoPrepared->fetchColumn($column_number);}

    public function fetchObject ($class_name = "stdClass", $ctor_args = [] ) { return $this->pdoPrepared->fetchObject($class_name,$ctor_args);}
    /*public function getAttribute ( int $attribute ) : mixed
    public function getColumnMeta ( int $column ) : array
    public function nextRowset ( void ) : bool
    public function rowCount ( void ) : int
    public function setAttribute ( int $attribute , mixed $value ) : bool
    public function setFetchMode ( int $mode ) : bool*/

    /**
     * @return \PDOStatement
     */
    public function getPdoPrepared(): \PDOStatement
    {
        return $this->pdoPrepared;
    }
}
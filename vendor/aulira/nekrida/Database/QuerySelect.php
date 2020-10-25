<?php

namespace Nekrida\Database;

class QuerySelect extends QueryTrait
{
    //Array [alias=>column/expression] / [column/expression]
    protected $selects = [];

    protected $selectOptions = [];

    // ['join','Table',['u.id','=','t.uid'],'al > 0']
    protected $joins = [];

    protected $groupBy = [];

    protected $having = [];

    protected $orderBy = [];

    protected $limit;

    protected $offset;

    //SQL FUNCTIONS

    public function having($item,$sign,$value) {
        $this->having[] = [$item,$sign,$value];
        return $this;
    }

    public function groupBy($columns) {
        foreach ($columns as $column)
            $this->groupBy[] = $column;
        return $this;
    }

    public function orderBy($columns) {
        foreach ($columns as $key => $value)
            if (is_int($key))
                $this->orderBy[$value] = 'ASC';
            else
                $this->orderBy[$key] = $value;
        return $this;
    }

    public function select($select = []) {
        if (!is_array($select)) $select = [$select];
        $this->selects = array_merge($this->selects,$select);
        return $this;
    }

    /**
     * SQL select options like DISTINCT
     * @param array $options
     */
    public function options($options) {
        $this->selectOptions = $options;
    }

    public function limit($limit, $offset = 0) {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

        //JOINS

    public function joinTrait($type,$table,$ons) {
        if (class_exists($table))
            $table = $table::TABLE_NAME;

        $arr = [$type,$table];
        foreach ($ons as $on)
            $arr[] = $on;
        $this->joins[] = $arr;
        return $this;
    }

    public function join($table,$ons = []) {
        return $this->joinTrait('JOIN',$table,$ons);
    }

    public function leftJoin($table,$ons = []) {
        return $this->joinTrait('LEFT JOIN',$table,$ons);
    }

    public function rightJoin($table,$ons = []) {
        return $this->joinTrait('RIGHT JOIN',$table,$ons);
    }

    public function fullJoin($table,$ons = []) {
        return $this->joinTrait('FULL JOIN',$table,$ons);
    }

            //DEPENDENT JOINS
    public function dependentJoinTrait ($type,$table,$ons) {
        $table = $this->getDependentTable($table);

        $arr = [$type,$table];
        foreach ($ons as $on)
            $arr[] = $on;
        $this->joins[] = $arr;
        return $this;
    }
    public function dependentJoin($table,$ons = []) {
        return $this->dependentJoinTrait('JOIN',$table,$ons);
    }

    public function dependentLeftJoin($table,$ons = []) {
        return $this->dependentJoinTrait('LEFT JOIN',$table,$ons);
    }

    public function dependentRightJoin($table,$ons = []) {
        return $this->dependentJoinTrait('RIGHT JOIN',$table,$ons);
    }

    public function dependentFullJoin($table,$ons = []) {
        return $this->dependentJoinTrait('FULL JOIN',$table,$ons);
    }

    /**
     * SQL join on column and value (Value is screened)
     * @param $item
     * @param $sign
     * @param $value
     * @return $this
     */
    public function on($item,$sign,$value) {
        $this->joins[count($this->joins) -1][] = [$item,$sign,$value];
        return $this;
    }

    /**
     * SQL join of 2 columns
     * @param $item1
     * @param $sign
     * @param $item2
     * @return $this
     */
    public function onA($item1,$sign,$item2) {
        $this->joins[count($this->joins) -1][] = "$item1 $sign $item2";
        return $this;
    }

    //BUILD

    public function build($preparedItems = 0) {
        $preparedCount = 0;
        //SELECT
        $select = [];
        foreach ($this->selects as $alias => $column) {
            $select[] = $column . (is_numeric($alias) ? '' : ' as "'.$alias.'"');
        }
        $tableAlias = $this->getTableAlias($this->table);
        if (empty($select)) $select = ['*'];
        //JOIN
        $joins = [];
        foreach ($this->joins as $join) {
            $joinStrings = [];

            for ($i = 2; $i < count($join); $i++)
                $joinStrings[] = $this->quoteIfArray($join[$i],$preparedCount,$preparedItems);

            $joins[] = $join[0] . ' '.$join[1].' '.$this->getTableAlias($join[1]).' ON '. implode(' AND ',$joinStrings);
        }
        $joins = implode(' ',$joins);
        //WHERE
        if (!$this->wheres)
            $wheres = '';
        else {
            $whereStrings = [];
            foreach ($this->wheres as $item)
                $whereStrings[] = $this->quoteIfArray($item,$preparedCount,$preparedItems);
            $wheres = 'WHERE ' . implode(' AND ', $whereStrings);
        }
        //GROUP BY
        if (empty($this->groupBy))
            $groupBy = '';
        else
            $groupBy = 'GROUP BY '. implode(',',$this->groupBy);

        //HAVING
        if (!$this->having)
            $having = '';
        else {
            $having = [];
            foreach ($this->having as $where) {
                $whereStrings = [];
                foreach ($where as $item)
                    $whereStrings[] = $this->quoteIfArray($item,$preparedCount,$preparedItems);
                $wheres = 'HAVING ' . implode(' AND ', $whereStrings);
            }
        }

        //ORDER BY
        if (empty($this->orderBy))
            $orderBy = '';
        else {
            $orderByS = [];
            foreach ($this->orderBy as $column => $type)
                $orderByS[] = $column . ' '.$type;
            $orderBy = 'ORDER BY '.implode(',',$orderByS);
        }

        //LIMIT
        if (empty($this->limit))
            $limit = '';
        else
            if (empty($this->offset))
                $limit = 'LIMIT '. $this->limit;
            else
                $limit = 'LIMIT '. $this->offset . ', '.$this->limit;
        $sql = 'SELECT '.implode(' ',$this->selectOptions).' '.implode(',',$select) .' FROM '.$this->table.' '.$tableAlias.' '.$joins.' '.$wheres.' '.$groupBy.' '.$having.' '.$orderBy. ' '.$limit;

        $this->preparedStatement = $sql;
        $this->isPrepared = $preparedItems;
    }
}
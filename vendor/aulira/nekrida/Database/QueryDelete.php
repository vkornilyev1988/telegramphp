<?php

namespace Nekrida\Database;


class QueryDelete extends QueryTrait
{
    //Array [alias=>column/expression] / [column/expression]
    /**
     * Here used as returning select statement
     * @var array
     */
    protected $selects = [];

    public function select($select = []) {
        if (!is_array($select)) $select = [$select];
        $this->selects = array_merge($this->selects,$select);
        return $this;
    }

    /**
     * Builds the query to be passed to the database
     * @param int $preparedItems How many items are to be prepared before executing
     * @return void
     */
    public function build($preparedItems = 0)
    {
        $preparedCount = 0;
        if ($this->selects) {
            $select = [];
            foreach ($this->selects as $alias => $column) {
                $select[] = $column . (is_numeric($alias) ? '' : ' as "' . $alias . '"');
            }

            $select = ' RETURNING '.implode(',',$select);
            }
        else {
            $select = '';
        }

        if (!$this->wheres)
            $wheres = '';
        else {
            $whereStrings = [];
            foreach ($this->wheres as $item)
                $whereStrings[] = $this->quoteIfArray($item,$preparedCount,$preparedItems);
            $wheres = 'WHERE ' . implode(' AND ', $whereStrings);
        }

        $sql = 'DELETE FROM '.$this->table.' '.$wheres.' '.$select;

        $this->preparedStatement = $sql;
        $this->isPrepared = $preparedItems;
    }
}
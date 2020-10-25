<?php

namespace Nekrida\Database;


class QueryUpdate extends QueryTrait
{
    protected $columns = [];

    protected $values = [];

    protected $rawValues = [];

    /**
     * Set columns to be updated in query
     * @param array $columnsArray
     * @return $this
     */
    public function columns($columnsArray) {
        $this->columns = $columnsArray;
        return $this;
    }

    /**
     * Add columns to the query
     * @param array $columns
     * @return $this
     */
    public function addColumns($columns) {
        $this->columns = array_values(array_unique(array_merge($this->columns,$columns)));
        return $this;
    }

    /**
     * Sets question marks to for prepare-execute. The number of question marks = number of columns.
     * @return $this
     */
    public function prepareRow() {
        $this->values = [];
        foreach ($this->columns as $column) {
            $this->values[] = '?';
        }
        return $this;
    }

    /**
     * Set column and value to update. Value is quoted.
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key,$value) {
        $this->values[count($this->columns)] = $value;
        $this->columns[count($this->columns)] = $key;

        return $this;
    }

    /**
     * Set column and value to update. Value is not quoted
     * @param $key
     * @param $value
     * @return $this
     */
    public function setRaw($key,$value) {
        $this->rawValues[count($this->columns)] = true;

        $this->values[count($this->columns)] = $value;
        $this->columns[count($this->columns)] = $key;

        return $this;
    }

    /*public function value(...$values) {
        $this->values[] = $values;
        return $this;
    }*/

    public function values (...$values) {
        foreach ($values as $value)
            $this->values[] = $value;
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
        //SET
        $update = [];
        for ($i = 0; $i < count($this->columns); $i++) {
            $update[] = ''.$this->columns[$i].' = '. (isset($this->rawValues[$i]) ? $this->values[$i] : ($preparedItems ? $this->quoteCount($this->values[$i], $preparedCount, $preparedItems) : $this->quote($this->values[$i])));
        }
        //WHERE
        if (!$this->wheres)
            $wheres = '';
        else {
            $whereStrings = [];
            foreach ($this->wheres as $item)
                $whereStrings[] = $this->quoteIfArray($item,$preparedCount,$preparedItems);
            $wheres = 'WHERE ' . implode(' AND ', $whereStrings);
        }

        $sql = "UPDATE ".$this->table." SET ".implode(',',$update).' '.$wheres;

        $this->preparedStatement = $sql;
        $this->isPrepared = $preparedItems;
    }
}
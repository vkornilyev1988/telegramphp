<?php

class QueryItem
{
    //GLOBAL ATTRIBUTES
    protected $options;

    protected $table;

    protected $tableAlias;

    protected $wheres;

    //SELECT

    protected $selects;

    protected $joins;

    protected $groupBy;

    protected $orderBy;

    protected $having;

    protected $limit;

    protected $offset;


    //UPDATE, INSERT
    protected $columns;

    protected $values;

    //ON DUPLICATE KEY UPDATE
    protected $columns2;

    protected $values2;


    //GLOBAL METHODS

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function dependentTable($table) {

    }


    //SELECT
    public function select($columns) {

    }

}
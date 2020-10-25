<?php

namespace Nekrida\Database;

class Query
{
    const TABLE_NAME = 'models';
    const ID_COLUMN = 'id';

    /** @var string|object database driver object */
    public static $databaseName = 'main';

    /**
     * @param array $columns
     * @return QueryInsert
     */
    public static function insertColumns($columns) {
        $a = new QueryInsert(static::TABLE_NAME,static::$databaseName);
        $a->columns($columns);
        return $a;
    }

    /**
     * @param $options
     * @return QueryInsert
     */
    public static function insertSet($options) {
        $a = new QueryInsert(static::TABLE_NAME,static::$databaseName);
        $keys =[]; $pr = [];
        foreach ($options as $key => $value) {
            $keys[] = $key;
            $pr[] = $value;
        }
        return $a->columns($keys)->values($pr);
    }

    /**
     * Delete row by table id. Shortcut for self::delete()->where('id','=','?')->query([$id])
     * @param $id
     * @return QueryDelete
     */
    public static function deleteById($id) {
        return self::delete()->where(self::ID_COLUMN,'=','?')->query([$id]);
    }

    /**
     * Init delete query
     * @return QueryDelete
     */
    public static function delete() {
        $a = new QueryDelete(static::TABLE_NAME,static::$databaseName);
        return $a;
    }

    /**
     * Init update query
     * @param $options array of key-value pairs
     * @return QueryUpdate
     */
    public static function updateSet($options) {
        $a = new QueryUpdate(static::TABLE_NAME,static::$databaseName);
        foreach ($options as $key=>$value) {
            $a->set($key, $value);
        }
        return $a;
    }

    /**
     * Init update query and set columns
     * @param $keys array of columns to update
     * @return QueryUpdate
     */
    public static function updateColumns($keys) {
        $a = new QueryUpdate(static::TABLE_NAME,static::$databaseName);
        $a->columns($keys);
        return $a;
    }

    /**
     * Init select query and choose columns to select
     * @param array $select columns to select either "column alias" => "column name" or "column name 1", "column name 2". Mixing is allowed
     * @return QuerySelect
     */
    public static function select($select = []) {
        $a = new QuerySelect(static::TABLE_NAME,static::$databaseName);
        return $a->select($select);
    }

    /**
     * Shortcut for self::select()->query()->fetchAll($fetchStyle)
     * Returns all rows from the table
     * @param int $fetchStyle PDO fetch_style
     * @return array
     */
    public static function selectAll($fetchStyle = 2) {
        $a = new QuerySelect(static::TABLE_NAME,static::$databaseName);
        return $a->select()->query()->fetchAll($fetchStyle);
    }

    /**
     * Shortcut for self::get($select)->where('id','=','?')->query([$id])->fetch($fetchStyle)
     * Returns row with the specific ID
     * @param $id
     * @param array $select {@see Query::get()}
     * @param int $fetchStyle PDO fetch_style
     * @return QuerySelect
     */
    public static function getById($id,$select = [],$fetchStyle = 2) {
        $a = new QuerySelect(static::TABLE_NAME,static::$databaseName);
        return $a->select($select)->where(static::ID_COLUMN,'=','?')->query([$id])->fetch($fetchStyle);
    }
}
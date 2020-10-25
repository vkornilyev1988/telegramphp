# Nekrida\Database\Query

## Class static methods

| Name | Description |
|------|-------------|
| insertColumns | |
| insertSet | |
| delete | |
| deleteById | |
| updateSet | |
| updateColumns | |
| select | |
| selectAll | |
| getById | |

### Query::insertColumns

```php 
public static function insertColumns(array $columns)
```

**Return Values**

`QueryInsert`

### Query::insertSet


## SQL query methods

* table

```php
public function table($table)
```

* dependentTable

```php
public function dependentTable($table)
```

* where($item,$sign,$value)

SQL Where clause. Multiple wheres form AND clause. Value is quoted

```php
public function where($item,$sign,$value)
```
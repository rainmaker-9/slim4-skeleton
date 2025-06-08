<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use PDO;
use PDOException;

class Model
{
  /**
   * @var string $table
   * Name of the Table
   */
  protected string $table = '';

  /**
   * @var string $primaryKey
   * Table's Primary Key
   */
  protected string $primaryKey = 'id';

  /**
   * @var array $fields
   * Fields to fill while using any DML. Specify column names in the form of array.
   */
  protected array $fields = [];

  /**
   * @var string $method
   * Default method to retrieve/fetch record. Either in the form of array or object. Default = array
   */
  protected string $method = 'array';

  /**
   * @var string $columns
   * Columns to select in the query. Default = *
   */
  protected string $columns = '*';

  /**
   * @var PDO $link
   * PDO Connection Object
   */
  private PDO $link;

  /**
   * Model constructor.
   * @throws PDOException
   */
  public function __construct()
  {
    $db = new Database;
    $this->link = $db->getConnection();
  }

  /**
   * Returns the PDO connection object.
   * @return PDO
   */
  public function getConnection(): PDO
  {
    return $this->link;
  }

  /**
   * Set the table name.
   * @param string $table
   */
  public function setTable(string $table): void
  {
    $this->table = $table;
  }

  /**
   * Set the primary key column name.
   * @param string $primaryKey
   */
  public function setPrimaryKey(string $primaryKey): void
  {
    $this->primaryKey = $primaryKey;
  }

  /**
   * Set the fields to fill while using any DML.
   * @param array $fields
   */
  public function setFields(array $fields): void
  {
    $this->fields = $fields;
  }

  /**
   * Sets the method to retrieve/fetch record. Either in the form of array or object.
   * @param string $method
   * @throws Exception
   */
  public function setMethod(string $method)
  {
    if (in_array($method, ['array', 'object'])) {
      $this->method = $method;
    } else {
      throw new Exception("Invalid method type. Use 'array' or 'object'.");
    }
  }

  /**
   * Get all records from the table.
   * @return array|object
   */
  public function getAll()
  {
    $result = $this->link->query("SELECT " . $this->columns . " FROM `" . $this->table . "`")->fetchAll();
    return $this->method === 'array' ? $result : json_decode(json_encode($result));
  }

  /**
   * Get all records from the table where a specific column matches a value.
   * @param mixed $value The value to match against the column.
   * @param string|null $column The column to match against. If null, uses the primary key.
   * @param int $paramType The PDO parameter type for the value. Default is PDO::PARAM_STR.
   * @return array|object
   */
  public function getAllWhere($value, $column = null, int $paramType = PDO::PARAM_STR): array|object
  {
    $col_name = empty($column) ? $this->primaryKey : $column;
    $stmt = $this->link->prepare("SELECT " . $this->columns . " FROM `" . $this->table . "` WHERE `" . $col_name . "` = :param");
    $stmt->bindParam(":param", $value, $paramType);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $this->method === 'array' ? $result : json_decode(json_encode($result));
  }

  /**
   * Get a single record from the table where a specific column matches a value.
   * @param mixed $value The value to match against the column.
   * @param string|null $column The column to match against. If null, uses the primary key.
   * @param int $paramType The PDO parameter type for the value. Default is PDO::PARAM_STR.
   * @return array|object
   */
  public function getSingleWhere($value, $column = null, int $paramType = PDO::PARAM_STR): array|object
  {
    $col_name = empty($column) ? $this->primaryKey : $column;
    $stmt = $this->link->prepare("SELECT " . $this->columns . " FROM " . $this->table . " WHERE " . $col_name . " = :param LIMIT 0,1");
    $stmt->bindParam(":param", $value, $paramType);
    $stmt->execute();
    $result = $stmt->fetch();
    return $this->method === 'array' ? $result : json_decode(json_encode($result));
  }

  /**
   * Executes Raw SQL Query and returns query results as an array.
   * @param string $query The Actual SQL Query to execute
   * @return array|object
   */
  public function query(string $query): array|object
  {
    return $this->method === 'array' ? $this->link->query($query)->fetchAll() : json_decode(json_encode($this->link->query($query)->fetchAll()));
  }

  /**
   * Executes SQL Query using Prepared Statements and returns query results as an array of arrays or arrays of objects. FALSE is returned on failure.
   * @param string $query The Actual SQL Query to execute
   * @param array $params Parameters for the Prepared Statement
   * @return array|object|bool
   */
  public function preparedQuery(string $query, array $params): array|object|bool
  {
    $stmt = $this->link->prepare($query);
    $stmt->execute($params);
    return $this->method === 'array' ? $stmt->fetchAll() : json_decode(json_encode($stmt->fetchAll()));
  }

  /**
   * Executes SQL Query using Prepared Statements and returns single row as an array or object. FALSE is returned on failure.
   * @param string $query The Actual SQL Query to execute
   * @param array $params Parameters for the Prepared Statement
   * @return array|object|bool
   */
  public function preparedQuerySingle(string $query, array $params): array|object|bool
  {
    $stmt = $this->link->prepare($query);
    $stmt->execute($params);
    return $this->method === 'array' ? $stmt->fetch() : $stmt->fetchObject();
  }

  /**
   * Fires an Insert query onto a table. Returns TRUE on success or FALSE on failure.
   * @param array $data Insert field list as an assocative array. Example: ['column_name' => value];
   * @return bool|string
   * @throws PDOException
   */
  public function insert($data)
  {
    $keys = array_keys($data);

    try {
      $stmt = $this->link->prepare("INSERT INTO `" . $this->table . "` (" . sprintf("`%s`", implode("`,`", $keys)) . ") VALUES (" . sprintf(":%s", implode(",:", $keys)) . ");");
      return $stmt->execute($data);
    } catch (Exception $e) {
      throw new PDOException($e->getMessage(), $e->getCode());
    }
  }

  /**
   * Executes a prepared statement for inserting data into the database.
   * @param string $query The SQL query to execute.
   * @param array $params The parameters to bind to the query.
   * @return bool Returns TRUE on success or FALSE on failure.
   */
  public function insertPrepared(string $query, array $params): bool
  {
    $stmt = $this->link->prepare($query);
    return $stmt->execute($params);
  }

  /**
   * Fires an Update query onto a table. Returns TRUE on success or FALSE on failure
   * @param array $data Update field list as an assocative array. Example: ['column_name' => value];
   * @param array $where Where field list as an assocative array. This will be appended after WHERE clause Example: ['column_name' => value];
   * @return bool
   * @throws PDOException
   */
  public function update($data, $where = [])
  {
    try {
      $updateKeys = array_keys($data);
      $updateFields = [];
      foreach ($updateKeys as $key) {
        $updateFields[] = "`$key` = :$key";
      }
      if (count($where) > 0) {
        $whereKeys = array_keys($where);
        $whereClauseFields = [];
        foreach ($whereKeys as $key) {
          $whereClauseFields[] = "`$key` = :$key";
        }
        $stmt = $this->link->prepare("UPDATE `" . $this->table . "` SET " . implode(", ", $updateFields) . " WHERE " . implode(", ", $whereClauseFields));
        return $stmt->execute(array_merge($data, $where));
      } else {
        $stmt = $this->link->prepare("UPDATE `" . $this->table . "` SET " . implode(", ", $updateFields));
        return $stmt->execute($data);
      }
    } catch (Exception $e) {
      throw new PDOException($e->getMessage(), $e->getCode());
    }
  }

  /**
   * Fires a Delete query onto a table. Returns TRUE on success or FALSE on failure
   * @param string $value Value for Primary Key Field or $column (if second parameter is provided).
   * @param string $column [optional] If you want to provide different column name other than Primary Key column for WHERE clause.
   * @return bool
   * @throws PDOException
   */
  public function delete($value, $column = '')
  {
    try {
      $col_name = empty($column) ? $this->primaryKey : $column;
      $stmt = $this->link->prepare("DELETE FROM `" . $this->table . "` WHERE `" . $col_name . "` = :param");
      $stmt->bindParam(":param", $value);
      return $stmt->execute();
    } catch (Exception $e) {
      throw new PDOException($e->getMessage(), $e->getCode());
    }
  }

  /**
   * Sets the columns to select in the query.
   * @param mixed ...$columns The columns to select. If no columns are provided, defaults to '*'.
   * @return void
   */
  public function select(...$columns): void
  {
    if (gettype($columns) === 'array' && count($columns) > 0) {
      $this->columns = implode(",", $columns);
    } else {
      $this->columns = '*';
    }
  }

  /**
   * Adds a JOIN clause to the table for complex queries.
   * @param string $joinTable The table to join.
   * @param string $onCondition The ON condition for the join.
   * @param string $type The type of join: INNER, LEFT, RIGHT, etc.
   * @return void
   */
  public function join(string $joinTable, string $onCondition, string $type = 'INNER'): void
  {
    // If table already contains a JOIN, just append
    if (stripos($this->table, ' join ') !== false) {
      $this->table .= " $type JOIN $joinTable ON $onCondition";
    } else {
      $this->table = "{$this->table} $type JOIN $joinTable ON $onCondition";
    }
  }

  /**
   * Returns the last inserted ID from the last INSERT query.
   * @return string
   * @throws PDOException
   */
  public function insertId(): string
  {
    return $this->link->lastInsertId();
  }
}

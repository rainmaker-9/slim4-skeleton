<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class Database
 * @package App\Core
 * 
 * This class handles the database connection using PDO.
 * It initializes the connection with the database credentials
 * from environment variables.
 * It also provides a method to get the database connection.
 */
class Database
{
  /**
   * Database connection instance.
   * @var \PDO
   */
  public \PDO $db;

  /**
   * Database constructor.
   * @throws \PDOException
   */
  public function __construct()
  {
    $dsn = Helpers::env('DB_DRIVER') . ":host=" . Helpers::env('DB_HOST') . ";port=" . Helpers::env('DB_PORT') . ";dbname=" . Helpers::env('DB_NAME') . ";charset=" . Helpers::env('DB_CHARSET');
    $this->db = new \PDO($dsn, Helpers::env('DB_USER'), Helpers::env('DB_SECRET'), [
      \PDO::ATTR_EMULATE_PREPARES   => false,
      \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
      \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '" . Helpers::env('DB_CHARSET', Constants::CHARSET) . "' COLLATE '" . Helpers::env('DB_COLLATION', Constants::COLLATION) . "';"
    ]);
  }

  /**
   * Get the database connection.
   * @return \PDO|null
   * @throws \PDOException
   */
  public function getConnection()
  {
    try {
      return $this->db;
    } catch (\PDOException $exception) {
      error_log($exception->getMessage());
    }
  }
}

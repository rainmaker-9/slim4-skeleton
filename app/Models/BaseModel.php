<?php

namespace App\Models;

use App\Core\Model;

class BaseModel extends Model
{
  /**
   * @var string $table
   * Name of the Table
   */
  protected string $table = 'base_table';

  /**
   * @var string $primaryKey
   * Table's Primary Key
   */
  protected string $primaryKey = 'id';

  /**
   * @var array $fields
   * Fields to fill while using any DML. Specify column names in the form of array.
   */
  protected array $fields = ['id', 'title', 'created_at', 'updated_at'];

  /**
   * @var string $method
   * Default method to retrieve/fetch record. Either in the form of array or object. Default = array
   */
  protected string $method = 'array';
}

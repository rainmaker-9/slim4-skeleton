<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class Constants
 * @package App\Core
 *
 * This class defines various constants used throughout the application.
 */
class Constants
{
  /**
   * @var string The default date format used in the application.
   */
  public const DATE_FORMAT = 'Y-m-d H:i:s';

  /**
   * @var string The default time zone used in the application.
   */
  public const TIMEZONE = 'GMT';

  /**
   * @var string The default date format for displaying dates in the application.
   */
  public const DISPLAY_DATE_FORMAT = 'd M Y';

  /**
   * @var string The default time format used in the application.
   */
  public const TIME_FORMAT = 'H:i:s';

  /**
   * @var string The default date and time format used in the application.
   */
  public const DATETIME_FORMAT = 'Y-m-d H:i:s';

  /**
   * @var string The default locale used in the application.
   */
  public const LOCALE = 'en_US';

  /**
   * @var string The default currency used in the application.
   */
  public const CURRENCY = 'INR';

  /**
   * @var string The default language used in the application.
   */
  public const LANGUAGE = 'en';

  /**
   * @var string The default character set used in the application.
   */
  public const CHARSET = 'utf8mb4';

  /**
   * @var string The default collation used in the application.
   */
  public const COLLATION = 'utf8mb4_general_ci';
}

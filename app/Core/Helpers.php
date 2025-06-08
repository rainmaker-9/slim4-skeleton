<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Class Helpers
 * @package App\Core
 *
 * This class provides various helper functions for formatting dates, currencies,
 * compressing images, and managing environment variables.
 */
class Helpers
{
  /**
   * Get the value of an environment variable.
   *
   * @param string $key The name of the environment variable.
   * @param mixed $default The default value to return if the variable is not set.
   * @return mixed The value of the environment variable or the default value.
   */
  static function env(string $key, mixed $default = null): mixed
  {
    if (!isset($_SERVER[$key])) {
      return $default;
    }

    if (is_array($_SERVER[$key])) {
      return $_SERVER[$key];
    }

    return match (strtolower((string) $_SERVER[$key])) {
      'true'  => true,
      'false' => false,
      'null'  => null,
      default => $_SERVER[$key],
    };
  }

  /**
   * Format a date and time to GMT+5:30 (India Standard Time).
   *
   * @param string $datetime The datetime string to format.
   * @param string $format The format to use for the output.
   * @return string The formatted date and time.
   */
  public static function formatDateTimeToGMTIndia($datetime, $format = 'd-m-Y h:i A')
  {
    $date = new \DateTime($datetime, new \DateTimeZone('UTC'));
    if (static::env('APP_ENV') === 'production') {
      $date->setTimezone(new \DateTimeZone(static::env('APP_TIMEZONE', 'Asia/Kolkata')));
    }
    return $date->format($format);
  }

  /**
   * Format a number as currency in Indian Rupees.
   *
   * @param mixed $value The value to format.
   * @return string The formatted currency string.
   */
  public static function formatCurrencyToIndia(mixed $value)
  {
    $fmt = new \NumberFormatter('en_IN', \NumberFormatter::CURRENCY);
    $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
    return $fmt->formatCurrency(floatval($value), 'INR');
  }

  /**
   * Compress an image to WebP format.
   *
   * @param string $source The path to the source image.
   * @param string $destination The path to save the compressed image.
   * @param int $quality The quality of the compressed image (0-100).
   * @return string The path to the compressed image.
   */
  public static function compressImageToWebp(string $source, string $destination, int $quality): string
  {
    if (!file_exists($source)) {
      throw new \Exception("Source file does not exist: $source");
    }

    $info = getimagesize($source);
    if ($info === false) {
      throw new \Exception("Unable to get image info: $source");
    }

    switch ($info['mime']) {
      case 'image/jpeg':
        $image = imagecreatefromjpeg($source);
        break;
      case 'image/jpg':
        $image = imagecreatefromjpeg($source);
        break;
      case 'image/png':
        $image = imagecreatefrompng($source);
        break;
      case 'image/gif':
        $image = imagecreatefromgif($source);
        break;
      case 'image/webp':
        $image = imagecreatefromwebp($source);
        break;
      default:
        throw new \Exception("Unsupported image type: {$info['mime']}");
    }

    if (!$image) {
      throw new \Exception("Failed to create image from source: $source");
    }

    // Preserve true color for PNG and JPEG
    if (imageistruecolor($image)) {
      imagepalettetotruecolor($image);
    }

    // Save as WebP
    if (!imagewebp($image, $destination, $quality)) {
      imagedestroy($image);
      throw new \Exception("Failed to save WebP image: $destination");
    }

    imagedestroy($image);
    return $destination;
  }
}

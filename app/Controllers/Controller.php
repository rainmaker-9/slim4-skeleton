<?php

declare(strict_types=1);

namespace App\Controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class Controller
 * @package App\Controllers
 *
 * This class serves as a base controller for all other controllers in the application.
 * It provides common functionalities such as creating JSON responses and redirecting to different URLs.
 */
abstract class Controller
{
  public function __construct(
    protected Container $container
  ) {}

  /**
   * Creates a JSON response.
   *
   * @param Response $response
   * @param mixed    $data
   * @param int      $status
   * @param int      $flags
   *
   * @return Response
   */
  protected function json(Response $response, mixed $data, int $status = 200, int $flags = 0): Response
  {
    $response->getBody()->write((string) json_encode($data, $flags));

    return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
  }

  /**
   * Creates a redirect response.
   *
   * @param Response $response
   * @param string   $url
   * @param int      $status
   *
   * @return Response
   */
  protected function redirect(Response $response, string $url, int $status = 302): Response
  {
    return $response->withStatus($status)->withHeader('Location', $url);
  }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class CandidatesController
 * @package App\Controllers
 *
 * This class handles the candidate registration and validation functionalities.
 * It includes methods to register a candidate, validate their entry, and get registration details by AADHAR number.
 * The data is stored in a MySQL database and file uploads are managed.
 */
class HomeController extends Controller
{
  /**
   * @var \App\Models\BaseModel $model
   * Model instance for database operations.
   */
  protected \App\Models\BaseModel $model;

  /**
   * HomeController constructor.
   *
   * @param \App\Models\BaseModel $model
   */
  public function __construct(\App\Models\BaseModel $model)
  {
    $this->model = $model;
  }

  /**
   * Get home contents from the database.
   *
   * @param Request $request
   * @param Response $response
   * @return Response
   */
  public function index(Request $request, Response $response): Response
  {
    // Fetch home contents from the database
    $homeContents = $this->model->getAll();

    // Render the home page view with the fetched contents
    return $this->json(
      $response,
      [
        'status' => 'success',
        'data' => $homeContents,
      ]
    );
  }
}

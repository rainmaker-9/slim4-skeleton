<?php

declare(strict_types=1);

use App\Controllers\HomeController;

return function (Slim\App $app): void {
  $app->get('/', [HomeController::class, 'index'])
    ->setName('home.index.get');
};

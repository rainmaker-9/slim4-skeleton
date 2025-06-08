<?php

declare(strict_types=1);

use App\Core\Helpers;

return [
  'app' => [
    'name'   => Helpers::env('APP_NAME', 'Slim4 Skeleton'),
    'env'    => Helpers::env('APP_ENV', 'production'),
    'debug'  => Helpers::env('APP_DEBUG', false),
    'locale' => 'en',
  ],
];

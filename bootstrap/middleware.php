<?php

declare(strict_types=1);

return function (Slim\App $app): void {
  $app->addBodyParsingMiddleware();
  $app->addRoutingMiddleware();
};

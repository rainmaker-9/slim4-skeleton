<?php

declare(strict_types=1);

use DI\ContainerBuilder;

$definitions = [
  'settings' => function (): array {
    return require 'settings.php';
  },
];

return (new ContainerBuilder())->addDefinitions($definitions)->build();

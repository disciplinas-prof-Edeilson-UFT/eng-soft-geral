<?php

require_once __DIR__ . '/vendor/autoload.php';

use Conex\MiniFramework\App;

try {
    $app = new App();
    $app->run();
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
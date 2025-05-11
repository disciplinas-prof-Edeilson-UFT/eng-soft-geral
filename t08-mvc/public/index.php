<?php
namespace public;

require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . '/../core/mvc/Router.php';
require_once __DIR__ . '/../core/http/Request.php';
require_once __DIR__ . '/../core/http/Response.php';
require_once __DIR__ . '/../core/mvc/controller.php';
require_once __DIR__ . '/../core/mvc/view.php';
require_once __DIR__ . '/../core/mvc/helpers/parameters.php';
require_once __DIR__ . '/..//routes.php';
require_once __DIR__ . '/../core/mvc/model-repository.php';

use core\app;

$app = new App();

try {
    $app->run();
} catch (\Exception $e) {
    echo $e->getMessage();
}

<?php
$basePath = str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', __DIR__));
define('BASE_URL', $basePath . '/');
?>

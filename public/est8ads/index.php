<?php

$frontController = dirname(__DIR__) . '/index.php';
$uri = $_SERVER['REQUEST_URI'] ?? '/est8ads';

$_SERVER['REQUEST_URI'] = '/est8ads' . ($uri === '/est8ads' ? '' : $uri);
$_SERVER['SCRIPT_FILENAME'] = $frontController;

require $frontController;

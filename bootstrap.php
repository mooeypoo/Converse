<?php
require_once "vendor/autoload.php";
require 'vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
use Doctrine\Common\ClassLoader;

$classLoader = new ClassLoader('Doctrine', 'vendor/doctrine');
$classLoader->register();

// TODO: This should REALLY be done better...
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
define( 'BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . $uri_parts[0] );


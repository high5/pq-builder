<?php

use Phalcon\DI,
    Phalcon\DI\FactoryDefault;

date_default_timezone_set('Asia/Tokyo');
ini_set('display_errors', 1);
error_reporting( E_ALL );

define( 'ROOT_PATH', __DIR__ );
define( 'APP_PATH', __DIR__ . '/../app' );
define( 'VENDOR_PATH', __DIR__ . '/../vendor' );

set_include_path(
    ROOT_PATH . PATH_SEPARATOR . get_include_path() );

include ROOT_PATH . "/../vendor/autoload.php";

$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
    ROOT_PATH
));

$classMap = array('__' => VENDOR_PATH . '/underscore.php');
$loader->registerClasses(
    $classMap
);


$loader->register();

$env = include __DIR__ . "/../.env.php";
$env['module'] = 'front';
define('APP_ENV', $env['env_mode']);
define('MODULE', $env['module']);


$di = new FactoryDefault();
DI::reset();

DI::setDefault($di);



<?php

namespace ChinchMS\Core;

use \Klein\Klein, \ORM, \PDO, \stdClass;

define('BASE_PATH', dirname(__DIR__).'/');
define('SRC_PATH', BASE_PATH.'src/');
define('APP_PATH', BASE_PATH.'app/');
define('STORAGE_PATH', BASE_PATH.'storage/');

require_once (SRC_PATH.'core/autoloader.php');

/*
 * Starting PHP session
 */
session_start();

$configuration = new Configuration();

$cinchms = new stdClass();
$cinchms->config = $configuration->getConfig();
$cinchms->databaseutils = new DatabaseUtils();
$cinchms->authentication = null;
$cinchms->basepath = BASE_PATH;
$cinchms->error_manager = new ErrorManager();

/*
 * Initializing MySQL PDO Connection Engine
 */
if($cinchms->config["mysql"]["host"] != "") {
    ORM::configure(array(
        'connection_string' => 'mysql:host='.$cinchms->config["mysql"]["host"].';port='.$cinchms->config["mysql"]["port"].';dbname='.$cinchms->config["mysql"]["db"],
        'username' => $cinchms->config["mysql"]["username"],
        'password' => $cinchms->config["mysql"]["password"],
        'driver_options' => array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ),
        'logging' => true,
        'caching' => false,
        'return_result_sets' => true
    ));
}

/*
 * Setup router
 */
$klein = new Klein();

/*
 * Add a title validator
 */
$klein->respond(function ($request, $response, $service, $app) {
    $service->addValidator('string', function ($str) {
        return preg_match('/^[0-9a-z-]++$/i', $str);
    });
});

/*
 * Includes all the routes under 'src/routes' recursively
 */
$routes = SRC_PATH.'routes/';
$dir = scandir($routes);

foreach($dir as $routes_entry) {
    if (!in_array($routes_entry, array(".",".."))) {
        if (is_dir($routes . $routes_entry)) {
            if (file_exists($routes . $routes_entry . '/routes.php')) {
                include $routes . $routes_entry . '/routes.php';
            }
        }
    }
}

/*
 * Error handling
 */
$klein->onHttpError(function($code, $klein) use ($cinchms) {
    $service = $klein->service();
    switch($code) {
        case 404:
            break;
        default:
            break;
    }
});

$klein->dispatch();


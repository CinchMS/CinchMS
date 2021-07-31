<?php

/*
 * Include composer
 */
require_once(BASE_PATH.'vendor/autoload.php');

require_once(SRC_PATH.'core/configuration.php');
require_once(SRC_PATH.'core/admin/authentication.php');

$coredir = SRC_PATH.'core/';
$coretree = scandir($coredir);

foreach($coretree as $routes_entry) {
    if (!in_array($routes_entry, array(".",".."))) {
        if (is_dir($coredir . $routes_entry)) {
            if (file_exists($coredir . $routes_entry . '/loadmodule.php')) {
                include $coredir . $routes_entry . '/loadmodule.php';
            }
        }
    }
}
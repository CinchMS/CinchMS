<?php

namespace CinchMS\Core;
use \ORM;


$klein->respond('GET', '/'.$cinchms->config["paths"]["admin"].'/login', function ($request, $response, $service) use($cinchms) {
    if(!$cinchms->authentication->isAuthenticated()) {
        /*ORM::forTable('admin')->create(array(
            'nickname' => 'admin',
            'email' => 'test@test.test',
            'password' => password_hash('admin', PASSWORD_DEFAULT)
        ))->save();*/
        $service->render(APP_PATH.'views/admin/authentication/login.phtml',
            array(
                "admin_path" => $cinchms->config["paths"]["admin"]
            )
        );
    } else {
        $response->redirect('/'.$cinchms->config["paths"]["admin"].'/dashboard', 302);
    }
});

include SRC_PATH . 'routes/admin/authentication/path/routes.php';

<?php

namespace CinchMS\Core;

$klein->with('/'.$cinchms->config["paths"]["admin"].'/users', function () use($klein, $cinchms) {
    $klein->respond('', function ($request, $response, $service) use($cinchms) {
        if($cinchms->authentication->isAuthenticated()) {
            $service->render(APP_PATH.'views/admin/acp/users/index.phtml');
        }
    });
});

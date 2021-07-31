<?php

namespace CinchMS\Core;

$klein->with('/'.$cinchms->config["paths"]["admin"].'/ucp', function () use($klein, $cinchms) {

    if($cinchms->authentication->isAuthenticated()) {
        $klein->respond('', function ($request, $response, $service) use($cinchms) {
            if($request->uri() === '/'.$cinchms->config["paths"]["admin"].'/ucp') {
                $service->render(APP_PATH . 'views/admin/acp/ucp/index.phtml');
            }
        });
    }

});

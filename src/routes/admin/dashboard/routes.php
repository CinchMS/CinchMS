<?php

namespace CinchMS\Core;

$klein->with('/'.$cinchms->config["paths"]["admin"].'/dashboard', function () use($klein, $cinchms) {

    if($cinchms->authentication->isAuthenticated()) {
        $klein->respond('', function ($request, $response, $service) use($cinchms) {
            if($request->uri() === '/'.$cinchms->config["paths"]["admin"].'/dashboard'){
                $service->render(APP_PATH.'views/admin/acp/dashboard/index.phtml');
            }
        });

        $klein->respond('/configuration', function ($request, $response, $service) use($cinchms) {
            if($cinchms->authentication->isAuthenticated()) {
                $service->render(APP_PATH.'views/admin/acp/dashboard/configuration/edit/index.phtml'); //TODO: Send config values to template
            }
        });

        $klein->respond('/external', function ($request, $response, $service) use($cinchms) {
            $service->render(APP_PATH.'views/admin/acp/dashboard/external/index.phtml'); //TODO: Send external resources settings to template
        });
    }

});

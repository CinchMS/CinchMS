<?php

$klein->respond('/'.$cinchms->config["paths"]["admin"].'/authentication/logout', function ($request, $response, $service) use($cinchms) {
    $result = $cinchms->authentication->isAuthenticated();

    if($result) {
        $cinchms->authentication->destroySession();
        $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login?logout=1', 302);
    } else {
        $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login', 302);
    }
});

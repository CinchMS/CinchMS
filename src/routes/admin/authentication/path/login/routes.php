<?php

$klein->respond('POST', '/'.$cinchms->config["paths"]["admin"].'/authentication/login', function ($request, $response, $service) use($cinchms) {
    try {
        $service->validateParam('email')->isEmail();

        $result = $cinchms->authentication->comparePassword($request->email, $request->password);

        if($result) {
            $user = ORM::forTable('admin')->where(
                array(
                    'email' => $request->email
                )
            )->findOne();
            if($user) {
                $cinchms->authentication->createSession($user->get('id'), $request->rememberme);
                $response->redirect('/'.$cinchms->config["paths"]["admin"].'/dashboard', 302);
            }
        } else {
            $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login?error=1', 302);
        }
    } catch (\Klein\Exceptions\ValidationException $e) {
        $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login?error=1', 302);
    }
});

$klein->respond('GET', '/'.$cinchms->config["paths"]["admin"].'/authentication/login', function ($request, $response, $service) use($cinchms) {
    if($cinchms->authentication->isAuthenticated()) {
        $response->redirect('/' . $cinchms->config["paths"]["admin"] . '/dashboard');
    } else if($request->uri() === '/'.$cinchms->config["paths"]["admin"]){
        $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login', 302);
    }
});

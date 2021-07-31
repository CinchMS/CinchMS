<?php

namespace CinchMS\Core;

$klein->with('/'.$cinchms->config["paths"]["admin"], function () use($klein, $cinchms) {
    $cinchms->authentication = new ACPAuthentication($cinchms->config);

    $klein->respond('', function ($request, $response, $service) use($cinchms) {
        if($cinchms->authentication->isAuthenticated()) {
            $response->redirect('/' . $cinchms->config["paths"]["admin"] . '/dashboard');
        } else if($request->uri() === '/'.$cinchms->config["paths"]["admin"]){
            $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login', 302);
        }
    });

    $klein->respond('/[*:page]',function ($request, $response, $service) use($cinchms) {
        $page = explode ('/', $request->page)[0];
        if($page !== 'login' && $page !== 'authentication'){
            if($cinchms->authentication->isAuthenticated()) {
                $service->render(
                    APP_PATH.'views/admin/acp/header.phtml',
                    array(
                        'username' => $_SESSION['username'],
                        'admin_path' => $cinchms->config["paths"]["admin"]
                    )
                );
                $submenu = APP_PATH.'views/admin/acp/'.$page.'/submenu.phtml';
                if(file_exists($submenu)) {
                    $service->render($submenu,
                        array(
                            "admin_path" => $cinchms->config["paths"]["admin"]
                        )
                    );
                }
            } else {
                $response->redirect('/'.$cinchms->config["paths"]["admin"].'/login', 302);
            }
        }
    });
});

/*
 * Includes all the routes under 'src/routes/admin' recursively
 */
$adm_routes = SRC_PATH.'routes/admin/';
$adm_dir = scandir($adm_routes);

foreach($adm_dir as $adm_entry) {
    if (!in_array($adm_entry, array(".",".."))) {
        if (is_dir($adm_routes . $adm_entry)) {
            if (file_exists($adm_routes . $adm_entry . '/routes.php')) {
                include $adm_routes . $adm_entry . '/routes.php';
            }
        }
    }
}

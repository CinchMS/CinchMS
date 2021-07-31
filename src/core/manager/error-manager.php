<?php

namespace CinchMS\Core;

class ErrorManager {

    function throw404($request, $response, $service, $cinchms) {
        $service->render(APP_PATH.'views/template/components/404.phtml',
            array(
                'home' => $cinchms->config["domain"],
                'pageTitle' => "Page not found",
                'ogTitle' => "Page not found",
                'ogDescription' => "",
                'ogImage' => "",
                'ogUrl' => "https://".$cinchms->config["domain"].$request->uri(),
                'ogSite_name' => "",
                'ogApp_id' => "",
                'twitterCard' => "",
                'twitterImageAlt' => "",
                'twitterSite' => ""
            )
        );
    }
}
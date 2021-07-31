<?php

namespace CinchMS\Core;

use \ORM;

$klein->respond(function ($request, $response, $service) use($klein, $cinchms) {
    if(substr($request->uri(), 0, 1) === '/') {
        $page = explode ('/', substr($request->uri(), 1))[0];
        if (!in_array($page, $cinchms->config["options"]["not_page_name"])) {
            $service->sport_menu = ORM::forTable('sports')
                                    ->where(array(
                                        "published" => 1
                                    ))->orderByAsc('name')->findMany();
            $service->service_menu = ORM::forTable('services')
                                        ->where(array(
                                            "published" => 1
                                        ))->orderByAsc('name')->findMany();
            $service->layout(APP_PATH.'views/template/components/page.phtml');
        }
    }
});
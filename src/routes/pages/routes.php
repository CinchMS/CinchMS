<?php

namespace CinchMS\Core;

$klein->respond('/[:title]', function ($request, $response, $service) use($klein, $cinchms) {
    try {
        if (!in_array($request->title, $cinchms->config["options"]["not_page_name"])) {
            $service->validateParam('title')->isString();
            if (file_exists(APP_PATH . 'views/public/content/' . $request->title . '.phtml')) {
                $array = array(
                    'latest' => array()
                );

                if (in_array($request->title, $cinchms->config["options"]["article_preview_allowed_pages"])) {
                    $loaderutil = $cinchms->databaseutils;
                    $previews = $loaderutil->getLastPublishedArticles($cinchms->config["options"]["latest_articles_preview_number"]);
                    $array['latest'] = $previews;
                }

                $header_manager = new HeaderManager(APP_PATH . 'views/public/header/' . $request->title . '.json');
                $header_manager->generateMeta($service);
                $service->render(APP_PATH . 'views/public/content/' . $request->title . '.phtml', $array);
            } else {
                $error = $cinchms->error_manager;
                $error->throw404($request, $response, $service, $cinchms);
            }
        }
    } catch(\Klein\Exceptions\ValidationException $exception) {
        $error = $cinchms->error_manager;
        $error->throw404($request, $response, $service, $cinchms);
    }
});

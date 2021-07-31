<?php

namespace CinchMS\Core;

$klein->respond('/resources/[*:file].[:type]', function ($request, $response, $service) use($cinchms) {
    $file =  $cinchms->basepath.'app/resources/';
    $tmp_type = '';

    switch( $request->param('type') ) {
        case "css":
            $file = $file.'css/'.$request->param('file').'.css';
            $tmp_type = "text/css";
            break;
        case "js":
            $file = $file.'javascript/'.$request->param('file').'.js';
            $tmp_type = "application/javascript";
            break;
    }

    if(file_exists($file)) {
        $etag = md5_file($file);
        header('Content-Type: '.$tmp_type);
        header('X-Content-Type-Options: nosniff');
        header('Cache-control: public');
        header('Pragma: cache');
        header('Etag: "'.$etag.'"');
        if($cinchms->config["options"]["long_term_cache"])
            header('Expires: '.gmdate('D, d M Y H:i:s', time() + 60 * 60).' GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($file)).' GMT');

        $response->body(file_get_contents($file));
    } else {
        $response->code(404)->body($service->render(APP_PATH.'views/public/404.phtml', array('home' => $cinchms->config["domain"])));
    }
});


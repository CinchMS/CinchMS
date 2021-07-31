<?php

namespace CinchMS\Core;

class HeaderManager {

    private $meta;

    function __construct($path) {
        $file = file_get_contents($path, true);
        $this->meta = json_decode($file, true);
    }

    function generateMeta($service) {
        if(!is_null($this->meta["og"]["title"])){
            $service->pageTitle = $this->meta["og"]["title"];
            $service->ogTitle = $this->meta["og"]["title"];
        }
        if(!is_null($this->meta["og"]["description"])){
            $service->description = $this->meta["og"]["description"];
            $service->ogDescription = $this->meta["og"]["description"];
        }
        if(!is_null($this->meta["og"]["image"])){
            $service->ogTitle = $this->meta["og"]["image"];
        }
        if(!is_null($this->meta["og"]["title"])){
            $service->ogTitle = $this->meta["og"]["title"];
        }
        if(!is_null($this->meta["og"]["url"])){
            $service->ogTitle = $this->meta["og"]["url"];
        }
        if(!is_null($this->meta["og"]["site_name"])){
            $service->ogTitle = $this->meta["og"]["site_name"];
        }
        if(!is_null($this->meta["og"]["app_id"])){
            $service->ogTitle = $this->meta["og"]["app_id"];
        }
        if(!is_null($this->meta["twitter"]["card"])) {
            $service->ogTitle = $this->meta["twitter"]["card"];
        }
        if(!is_null($this->meta["twitter"]["image:alt"])){
            $service->ogTitle = $this->meta["twitter"]["image:alt"];
        }
        if(!is_null($this->meta["twitter"]["site"])){
            $service->ogTitle = $this->meta["twitter"]["site"];
        }
    }
}
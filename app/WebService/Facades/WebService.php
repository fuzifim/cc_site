<?php

namespace WebService\Facades;

use Illuminate\Support\Facades\Facade;

class WebService extends Facade {

    protected static function getFacadeAccessor() { return 'webservice'; }

}
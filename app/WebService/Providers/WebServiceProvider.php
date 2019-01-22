<?php namespace WebService\Providers;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('webservice', function()
            {
                return new \WebService\WebService();
            });
    }

}
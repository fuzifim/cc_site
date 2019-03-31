<?php
namespace App\Providers;
use Illuminate\Session\SessionServiceProvider;
class SessionServiceProviderCustom extends SessionServiceProvider
{
    public function register()
    {
        $this->registerSessionManager();
        $this->registerSessionDriver();
        $this->app->singleton('App\Http\Middleware\StartSessionCustom');
    }
}
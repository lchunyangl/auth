<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/21
 * Time: 9:11
 */

namespace Chunyang\Auth;


use Illuminate\Support\Facades\Auth;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $configPath = __DIR__ . '/../config/web.php';
        $this->mergeConfigFrom($configPath, 'web');
    }

    public function boot()
    {
        Auth::extend('proxy', function ($app) {
            return $app->make(ProxyGuard::class);
        });
    }
}
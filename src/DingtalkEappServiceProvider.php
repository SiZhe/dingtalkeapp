<?php

namespace Ghlin\DingtalkEapp;

use Illuminate\Support\ServiceProvider;

class DingtalkEappServiceProvider extends ServiceProvider
{
    
    protected $defer = false; // 延迟加载服务
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/config/dingtalkeapp.php' => config_path('dingtalkeapp.php')]);
        $this->publishes([realpath (__DIR__ . '/views') => base_path ('resources/views/vendor/dingtalk')], 'view');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('dingtalkeapp', function () {
            return new DingtalkEapp();
        });
    }
    
    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['dingtalkeapp'];
    }
}

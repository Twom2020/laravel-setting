<?php


namespace Twom\Setting;


use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerMigrations();
        $this->registerConfig();
    }

    public function register()
    {
        //
    }

    protected function registerMigrations()
    {
        $this->publishes([
            realpath(__DIR__ . '/database/migrations') => database_path('migrations')
        ], 'migrations');
        $this->loadMigrationsFrom(realpath(__DIR__ . '/database/migrations'));
    }

    protected function registerConfig()
    {
        $this->publishes([
            realpath(__DIR__ . "/config/setting.php") => config_path('setting.php')
        ], 'config');
        $this->mergeConfigFrom(realpath(__DIR__ . '/config/setting.php'), 'setting');
    }
}

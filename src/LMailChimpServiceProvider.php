<?php
/**
 * Created by PhpStorm.
 * User: feikwok
 * Date: 25/3/18
 * Time: 12:17 AM
 */
namespace Feikwok\LMailChimp;

use Feikwok\LMailChimp\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LMailChimpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! defined('PACKAGE_PATH')) {
            define('PACKAGE_PATH', realpath(__DIR__ . '/laravel-mailchimp-list-management/'));
        }
        $this->registerResources();
        // $this->registerRoutes();
        $this->defineAssetPublishing();
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/routes/api.php';
        $this->app->make('Feikwok\LMailChimp\Http\Controllers\Api\ListApiController');
    }
    /**
     * Register the package Resources
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lmailchimp');
        $this->app->bind('MailChimpApiService', function($app){
            return new MailChimpApiService();
        });

    }
    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                PACKAGE_PATH.'tests' => base_path('tests'),
            ], 'laravel-mailchimp-list-managament');

        }
    }
}
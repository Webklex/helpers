<?php
/*
 * File:     HelperServiceProvider.php
 * Category: Provider
 * Author:   MSG
 * Created:  21.09.16 11:57
 * Updated:  -
 *
 * Description:
 *  -
 */

namespace webklex\helpers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider {

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register() {
        $this->commands([Commands\HelperMakeCommand::class,]);

        //include the active package helpers
        foreach (config('helpers.package_helpers', []) as $activeHelper) {

            $file = __DIR__ . '/Helpers/' . $activeHelper . '.php';
            if (file_exists($file)) require_once($file);
        }

        //load custom helpers with a mapper
        if (count(config('helpers.custom_helpers'))) {
            foreach (config('helpers.custom_helpers') as $customHelper) {

                $file = app_path() . '/' . $this->getHelpersDirectory() . '/' . $customHelper . '.php';
                if(file_exists($file)) require_once($file);
            }
        }else{
            //load custom helpers automatically
            foreach (glob(app_path() . '/' . $this->getHelpersDirectory() . '/*.php') as $file) {
                require_once($file);
            }
        }
    }

    /**
     * boot the service provider
     *
     * @return void
     */
    public function boot() {
        $this->publishes([__DIR__ . '/config/helpers.php' => config_path('helpers.php'),], 'config');
    }

    /**
     * get the directory the helpers are stored in
     *
     * @return mixed
     */
    public function getHelpersDirectory() {
        return config('helpers.directory', 'Helpers');
    }
}

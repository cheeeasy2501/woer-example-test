<?php
namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider {
    public function boot() { //получаем список модулей, которые надо подгрузить
        $modules = config("module.modules");
        if($modules) {
           foreach($modules as $module) {
               if(file_exists(__DIR__.'/'.$module.'/Routes/routes.php')) {
                   $this->loadRoutesFrom(__DIR__.'/'.$module.'/Routes/routes.php');
               }

               if(is_dir(__DIR__.'/'.$module.'/Views')) {
                   $this->loadViewsFrom(__DIR__.'/'.$module.'/Views', $module);
               }
           }
        }
    }

    public function register() {

    }
}

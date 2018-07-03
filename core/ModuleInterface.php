<?php
namespace dssource\basic\core;
/**
 * Interface ModuleInterface
 * @package dssource\modulemanager
 */
interface ModuleInterface
{
    /**
     * Initializes the module.
     */
    public function init();
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app);

    public static function menu();
}
?>
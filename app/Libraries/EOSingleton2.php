<?php


namespace App\Libraries;

use App\Models\Config;

class EOSingleton2 {

    private static $instance = null;



    private function __construct()
    {

        $this->config_status =  Config::where("key", 'eostatus')->first();
        $this->config_key =  Config::where("key", 'eokey')->first();

    }


    public static function getInstance()
    {
        if (self::$instance == null)
        {
            self::$instance = new EOSingleton2();
        }

        return self::$instance;
    }
}

<?php
use App\Models\Config;

use App\Libraries\EOSingleton2;
use App\Libraries\Temp;

/*
function p($array,$message =''){

    echo '<pre>-'.$message;
    print_r($array);
    echo '</pre>';
}
 
function pd($array,$message =''){

        p($array,$message);die();

}
function vdd($array){

    var_dump($array);die();

}
*/
function _config($key)
{

    /**/
    //\App\Models\Debug::create([
    //    'message'=>"incoming key $key"
    //]);
    // echo "incoming $key <***";
    $eo = EOSingleton2::getInstance();//instantiate on first call;
    if (strcmp($key,'eostatus')==0)// I know it is going to be costly
    {

        return $eo->config_status->value;
    }
    else if (strcmp($key,'eokey')==0){

        return $eo->config_key->value;

    }
    else { //we can make a call to the db

         $config = Config::where("key", $key)->first();
         return $config->value;
    }


}

/*
function log_($message){

    \App\Models\Debug::create(
        ["message"=>$message]
    );

}

function anchor($atts){
    $atts["label"]=$atts["html"];
    $t = new Temp("anchor",$atts);
    return $t;
}
*/


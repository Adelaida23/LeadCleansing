<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use App\Models\Lead;
//use Psy\Util\Str;
use App\Models\Config;

class EmailOversight2
{ 
    public static $key = 'dbef7bbb-c5f7-432c-a541-63a989fdd313'; //@todo : make it dynamic in settings
    public static $endpoint = 'https://api.emailoversight.com/api/EmailValidation';

    public static $debug = false;
    public static $mode='online';


    public static function call($email, $list_id='95052')
    {
        self::$mode = _config('eostatus');

        if (self::$mode=='emulate'){
            return self::fake($email);
        }

        if (self::$mode=='offline'){
            return null;
        }


        $endpoint = self::$endpoint;
        $client = new Client;
        $token = self::$key;
        $value = $email;

        try {
            $response = $client->request('GET', $endpoint, ['query' => [
                'apitoken' => $token,
                'listid' => $list_id,
                'email' => $value,
            ]]);
        }
        catch (\Exception $e){
            return false;
        }


        $statusCode = $response->getStatusCode();
        //echo $statusCode;die();
        if ($statusCode!=200){
            return false;
        }

      //  $content = $response->getBody();
        $content = json_decode($response->getBody(), true);
        return $content;


    }


    public static function fake($email){
        $out = [];
        if (!Str::endsWith($email, ["yahoo.com"])){  //GOOD

                            $out = [

                              "ListId" => 95052,
                              "Email" => "$email",
                              "Result" => "Verifiedd",
                              "ResultId" => 1,
                              "EmailDomainGroupId" => 5,
                              "EmailDomainGroup" => "GOOGLE",
                              "EmailDomainGroupShort" => "GOO",
                              "EOF" => -1,
                            ];

        }
        else {
            $out = ["ListId" => 95052,
                "Email" => "$email",
                "Result" => "Undeliverabledd",
                "ResultId" => 2,
                "EmailDomainGroupId" => 2,
                "EmailDomainGroup" => "YAHOO",
                "EmailDomainGroupShort" => "YAH",
                "EOF" => -1];
        }

        return $out;

    }
}

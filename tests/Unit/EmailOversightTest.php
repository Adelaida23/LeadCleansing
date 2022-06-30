<?php

namespace Tests\Unit;

#use PHPUnit\Framework\TestCase;

use App\Libraries\EmailOversight2;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class EmailOversightTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_verify()
    {
       // $eoId = '95052';
        //  $lead = request(['email']);
        //   $lead = "adhelita";
        $lead = "adelaida@gmail.com";
        if (_config('eostatus') != 'offline') { //emulatea and online, EOclass wull decide what to call
            if (!empty($lead)) {
                if (!empty($eoId)) {
                    $res = EmailOversight2::call($lead, $eoId);
                    //cachar los estados de la respuesta
                    print_r($res);
                } else {
                    print_r('no se especifico ListId');
                }
            } else {
                print_r('email no especificado');
            }
        } else {
            print_r('bad');
        }
        //  print_r($res);
    }
    
    public function test_queryLeads()
    {
        // Make call to application...
        /*
        $this->assertDatabaseHas('leads', [
            'email' => 'kurt.jolliff@trelleborg.com'
        ]);
        */      
        

    }
}

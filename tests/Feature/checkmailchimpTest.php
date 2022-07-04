<?php

namespace Tests\Feature;

use App\Libraries\Mailchimp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class checkmailchimpTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mailchimp_ping()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/pingMailchipm');



        $response->assertStatus(200);
    }
    public function test_ping_mailchimp()
    {
        $client = new \MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12',
            'server' => 'us12',
        ]);

        $response = $client->ping->get();
        print_r($response);
    }
    public function test_Api()
    {
        $mailchimp = new Mailchimp(['apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12', 'server' => 'us12']);
        /*
        if ($mailchimp->ping() === FALSE) {
            continue;
        }
        */
        $response = $mailchimp->ping->get();
        print_r($response);
    }
}

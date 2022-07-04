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

    public function test_ping()
    {
        $mailchimp = new Mailchimp(['apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12', 'server' => 'us12']);
        $response = $mailchimp->ping();
        print_r($response);
    }
    public function test_get_list()
    { //otener datos list include list_id
        $mailchimp = new Mailchimp(['apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12', 'server' => 'us12']);
        $response = $mailchimp->getLists();
        print_r($response);
    }
    public function test_merge_field()
    { //datos campos contacto
        $mailchimp = new Mailchimp(['apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12', 'server' => 'us12']);
        $response = $mailchimp->getMergeFields('8100a4643a');
        print_r($response);
    }
    public function test_addMemberList()
    {
        $client = new \MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12',
            'server' => 'us12',
        ]);

        $response = $client->lists->addListMember('8100a4643a', [
            "email_address" => "babyflory23@gmail.com",
            "status" => "subscribed",
        ]);
        print_r($response);
    }
    public function test_getMembersList()
    {
        $client = new \MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12',
            'server' => 'us12',
        ]);
        $response = $client->lists->getListMembersInfo("8100a4643a");
        print_r($response);
    }
    public function test_getListWithEmail()
    {
        $client = new \MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => 'e6ce965275b2c237e341f3876d34f802-us12',
            'server' => 'us12',
        ]);
        $response = $client->lists->getListMembersInfo("list_id", ['email_address']);
        print_r($response);
    }
}

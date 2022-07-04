<?php

namespace App\Http\Controllers;

use App\Libraries\EmailOversight2;
use App\Models\Lead;
use DateTime;
use Illuminate\Http\Request;
use App\Libraries\Mailchimp;
use MailchimpMarketing\ApiClient;

class LeadcheckController extends Controller
{
    public function store()
    {
        $list_emails = Lead::emailsLeadCheckSinceMonths();
        //  print_r($list_emails);
        for ($i = 0; $i < count($list_emails); $i++) {
            $lead = [
                'id' => $list_emails[$i]->id,
                'email' => $list_emails[$i]->email,
                'status' => $list_emails[$i]->status
            ];
            //llamar api
            $this->conectEmailOversightApi($lead);
        }
    }
    public function conectEmailOversightApi($lead)
    {
        //at the bd table configs change emulate for local 
        if (_config('eostatus') != 'offline') { //emulatea and online, EOclass wull decide what to call
            if (!empty($lead)) {
                $res = EmailOversight2::call($lead['email']);
                // print_r($res);
                if ($lead['status'] != $res['ResultId']) {
                    //print_r('cambio');
                    $campo_act = Lead::find($lead['id']);
                    $campo_act->status = $res['ResultId'];
                    //$campo_act->reason = $resultado[''] //preguntarle a arturo que valor actualiza reason
                    $now = new DateTime();
                    $fecha = $now->format('Y-m-d');
                    $campo_act->last_checked = $fecha;
                    $campo_act->emailoversight_check = 1;
                    $campo_act->save();
                    // $campo_act->domain = $resultado['EmailDomainGroup']; //pregntar que valor lo actualiza
                    //$campo_act->domain_country = //estes no

                } else {
                    print_r('iguales');
                    $campo_act = Lead::find($lead['id']);
                    //$campo_act = DB::table('leads')->where('id', '=', $objeto['id'])->first();
                    $now = new DateTime();
                    $fecha = $now->format('Y-m-d');
                    $campo_act->last_checked = $fecha;
                    $campo_act->emailoversight_check = 1;
                    $campo_act->save();
                }
            } else {
                print_r('email no especificado');
            }
        } else {
            print_r('offline');
        }
    }

    /*

    public function pingMailchimp()
    {

        $lead = Lead::where('md5', $value->hash)->first();
        if (!empty($lead->id)) {
            if (!empty($lead->lists)) {
                foreach ($lead->lists as $leadList) {
                    if (!is_null($leadList->list->list_id_external)) {
                        $account = $leadList->list->esp_account;
                        $mailchimp = new Mailchimp(['apiKey' => $account->key, 'server' => $account->server]);
                        if ($mailchimp->ping() === FALSE) {
                            continue;
                        }
                    }
                }
            }
        }
    }

    public function testPing()
    {
        $mailchimp = new Mailchimp(['apiKey' => $account->key, 'server' => $account->server]);
        if ($mailchimp->ping() === FALSE) {
            continue;
        }
    }

    public function doPing()
    {
        $client = new \MailchimpMarketing\ApiClient();
        //  $this->mailchimp = new \MailchimpMarketing\ApiClient();
        $client->setConfig([
            'apiKey' => 'YOUR_API_KEY',
            'server' => 'YOUR_SERVER_PREFIX',
        ]);

        $response = $client->ping->get();
        print_r($response);
    }
    public function example()
    {
        $supression = Suppression::select('email', 'hash')
            ->selectRaw("GROUP_CONCAT(advertiser_id) as advertiser_id")
            ->where('updated_esp', '=', '0')
            ->whereNotNull('email')
            ->groupBy('email', 'hash')
            ->limit(1000);
        $offset = 0;
        while (count($data = $supression->offset($offset)->get()) > 0) {
            foreach ($data as $value) {
                $advertiser = $value->advertiser_id;
                $lead = Lead::where('md5', $value->hash)->first();
                if (!empty($lead->id)) {
                    if (!empty($lead->lists)) {


                        foreach ($lead->lists as $leadList) {
                            if (!is_null($leadList->list->list_id_external)) {
                                $account = $leadList->list->esp_account;
                                $mailchimp = new Mailchimp(['apiKey' => $account->key, 'server' => $account->server]);
                                if ($mailchimp->ping() === FALSE) {
                                    continue;
                                }
                                $mergeFields = $mailchimp->getMergeFields($leadList->list->list_id_external);
                                if ($mergeFields === FALSE) {
                                    Suppression::updateSuppression($value->hash, ['updated_esp' => FALSE]);
                                    continue;
                                }
                                $field = $mailchimp->getMergeField($mergeFields, explode(',', $advertiser));
                                $mailchimp->setMergeField($leadList->list->list_id_external, $value->hash, $field);
                                Suppression::updateSuppression($value->hash, ['updated_esp' => TRUE]);
                            }
                        }
                    }
                } else {
                    //empty 
                    continue;
                }
            }
            $offset += 1000;
        }
        $date = new \DateTime(date('Y-m-d H:i:s'), new \DateTimeZone('America/New_York'));
        $event->log->end_at = $date->format('Y-m-d H:i:s');
        $event->log->save();
        $users = User::where('role_id', 1)->where('status', 1)->get();
        Notification::send($users, new OptizmoProcessNotification($event->log));
    }

    */
}

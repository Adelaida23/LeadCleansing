<?php

namespace App\Http\Controllers;

use App\Libraries\EmailOversight2;
use App\Models\Lead;
use DateTime;
use Illuminate\Http\Request;

class LeadcheckController extends Controller
{
    public function store()
    {
        $list_emails = Lead::emailsLeadCheckSinceMonths();
        print_r($list_emails);
        // $list_emails = Lead::listEmailsVerify();
        for ($i = 0; $i < count($list_emails); $i++) {
            //$status = $list_emails['status'];
            //$status = $list_emails->status;
            $lead = [
                'id' => $list_emails[$i]->id,
                'email' => $list_emails[$i]->email,
                'status' => $list_emails[$i]->status
            ];
            // print_r($lead);
            //llamar api
            $this->conectEmailOversightApi($lead);
            //print_r($list_emails[$i]->email);
            // $this->assertEmpty($list_emails[$i]->email);
        }
    }
    public function conectEmailOversightApi($lead)
    {
        // $lead = "adelaidadsfahoo.com";
        //at the bd table configs change emulate for local 
        if (_config('eostatus') != 'offline') { //emulatea and online, EOclass wull decide what to call
            if (!empty($lead)) {
                $res = EmailOversight2::call($lead['email']);
                print_r($res);
                //cachar los estados de la respuesta

                if ($lead['status'] != $res['ResultId']) {
                    print_r('cambio');

                    $campo_act = Lead::find($lead['id']);
                    $campo_act->status = $res['ResultId'];
                    //$campo_act->reason = $resultado[''] //preguntarle a arturo que valor actualiza reason
                    $now = new DateTime();
                    $fecha = $now->format('Y-m-d H:i:s');
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
                    $fecha = $now->format('Y-m-d H:i:s');
                    $campo_act->last_checked = $fecha;
                    $campo_act->emailoversight_check = 1;
                    $campo_act->save();
                }
                //  print_r($res);
            } else {
                print_r('email no especificado');
            }
        } else {
            print_r('offline');
        }
    }
}

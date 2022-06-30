<?php

namespace Tests\Feature;

use App\Libraries\EmailOversight2;
use App\Models\Config;
use App\Models\Lead;
use DateTime;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;

class EmailOversightTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_created_prueba()
    {

        $this->withoutExceptionHandling();
        $response = $this->post('/config_prue', [
            'key' => 'llavePrue',
            'value' => 'adsdhsdhdhsd',
            'active' => 1,
            'label' => 'prueba',
            'obs' => 'prueba',
            'grouped' => 'prue'
        ]);
        $response->assertOk();
        //  $this->assertCount(4, Config::all());
        $post = Config::first();
        $this->assertEmpty($post);
        //buscar un asset que confirme no vacio 
        // $this->assertCount(1,$post);
        //  $this->assertEquals($post->key, 'eostatus');
        //$this->assertEquals($post->user_id, 6);
    }
    public function test_getLeadList()
    {
        // $leads_list = DB::table('leads')->limit(500)->get();
        $leads_list = DB::table('leads')->orderByDesc('created_at')
            ->limit(5)->get(['email', 'status', 'reason', 'emailoversight_check', 'domain']); //'domain_country'
        print_r($leads_list);
        $this->assertCount(5, $leads_list);
    }

    //conect to api emailoversight  petition 
    public function test_emailOversight_api()
    {
        $lead = "adelaidadsfahoo.com";
        //at the bd table configs change emulate for local 
        if (_config('eostatus') != 'offline') { //emulatea and online, EOclass wull decide what to call
            if (!empty($lead)) {
                $res = EmailOversight2::call($lead);
                //cachar los estados de la respuesta
                //  if ($res[''])
                print_r($res);
            } else {
                print_r('email no especificado');
            }
        } else {
            print_r('offline');
        }
    }
    //recorrer for with multip emails
    public function test_emailOversightToMultipEmails()
    {
        $status = 4;  //0 to 4
        $objeto = [
            'email' => 'jpaxx@twc.com',
            'status' => 2,
            'reason' => 9,
            'emailoversight_check' => 1,
            'domain' => 'twc.com'
        ];
        $resultado = [
            'ListId' => 95052,
            'email' => 'jpaxx@twc.com',
            'Result' => 'verified',
            'ResultId' => 1,
            'EmailDomainGroupId' => 5,
            'EmailDomainGroup' => 'GOOGLE',
            'EmailDomainGroupShort' => 'GOO',
            'EOF' => -1
        ];
        $list_emails = Lead::listEmailsVerify();
        for ($i = 0; $i < count($list_emails); $i++) {
            //llamar metodo que se conecte a la api
            //recuperar respuesta api y comparar su estatus
            if ($objeto['status'] != $resultado['ResultId']) {
                //update()

                //actualizar fecha campo last_checked nuevo

            } else {
                //actualizar fecha campo last_checked nuevo
            }
            print_r($list_emails[$i]->email);
            // $this->assertEmpty($list_emails[$i]->email);
        }
        // $this->assertCount(5, $list_emails); 
        //print_r($list_emails);
    }

    //change last checked col
    public function test_update_lastChecked()
    {
        $this->withoutExceptionHandling();
        $objeto = [
            'id' => 62826,
            'email' => 'jpaxx@twc.com',
            'status' => 2,
            'reason' => 9,
            'emailoversight_check' => 1,
            'domain' => 'twc.com'
        ];
        $campo_act = Lead::find($objeto['id']);
        //$campo_act = DB::table('leads')->where('id', '=', $objeto['id'])->first();
        $now = new DateTime();
        $fecha = $now->format('Y-m-d H:i:s');
        $campo_act->last_checked = $fecha;
        $campo_act->emailoversight_check = 1;
        $campo_act->save();
        //comprobar si lo insertó
        $campo_act2 = Lead::find($objeto['id']);
        echo ($campo_act2);
        //  print_r($campo_act);
        //assertEquals($campo_act->email, 'jpaxx@twc.com');
        assertEquals($campo_act2->last_checked, $fecha);
    }
    public function test_update_change_status()
    {
        $status = 4;  //0 to 4
        $objeto = [
            'id' => 62826,
            'email' => 'jpaxx@twc.com',
            'status' => 2,
            'reason' => 9,
            'emailoversight_check' => 1,
            'domain' => 'twc.com'
        ];
        $resultado = [
            'ListId' => 95052,
            'email' => 'jpaxx@twc.com',
            'Result' => 'verified',
            'ResultId' => 1,
            'EmailDomainGroupId' => 5,
            'EmailDomainGroup' => 'GOOGLE',
            'EmailDomainGroupShort' => 'GOO',
            'EOF' => -1
        ];
        if ($objeto['status'] != $resultado['ResultId']) {
            $campo_act = Lead::find($objeto['id']);
            $campo_act->status = $resultado['ResultId'];
            //$campo_act->reason = $resultado[''] //preguntarle a arturo que valor actualiza reason
            $campo_act->emailoversight_check = 1;
            $campo_act->save();
            // $campo_act->domain = $resultado['EmailDomainGroup']; //pregntar que valor lo actualiza
            //$campo_act->domain_country = //estes no
        }
        //verificar ok
        $campo_act2 = Lead::find($objeto['id']);
        assertEquals($campo_act2->status, 1);
        echo ($campo_act2);
    }

  
    //query email
    public function test_query_get_leads_3months()
    {
        $fecha_atras = date('Y-m-d H:i:s', strtotime('d' . '-1 month')); //restar 3 en caso de 3 meses atras
        //$fecha_atras = date('Y-m-d ', strtotime('d' . '-1 month')); //restar 3 en caso de 3 meses atras
        //de fecha_atras toma todos los registros ordenalos descendente y toma 500 
        $leads_check_list = DB::table('leads')
            ->where('last_checked', '<=', $fecha_atras)
            ->orderByDesc('last_checked')
            ->limit(10)->get(['email']); //recuperar limit (500)
        assertCount(10, $leads_check_list);
        //echo $fecha_atras;
        echo $leads_check_list;
    }
    
    public function test_ListEmail_since_model(){
        $lista = Lead::emailsLeadCheckSinceMonths();
        assertCount(10, $lista);
    }

    public function test_post_check_leads()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/check_leads', [
            'key' => 'llavePrue'
        ]);
        $response->assertOk();
    }













    public function email_verify(Request $request)
    {
        $lead = $request->email;
        $resultado = "SIN RESPUESTA";
        $eoId = '95052';
        //$lead = "adhel1997zz@gmail.com";
        // $lead = $request['correo'];
        if (_config('eostatus') != 'offline') { //emulatea and online, EOclass wull decide what to call
            if (!empty($lead)) {
                if (!empty($eoId)) {
                    $res = EmailOversight::call($lead, $eoId);
                    if ($res) {
                        if ($res["ResultId"] != 1) {
                            $resultado = "EMAIL REJECT";
                        } else {
                            $resultado = "EMAIL SUCCESS";
                        }
                        return ['msg' => $resultado, 'arre_respuesta' => $res, 'success' => true];
                    } else {

                        $resultado =  'FORMATED EMAIL DON´T SPECIFIED';
                        return ['msg' => $resultado,  'success' => false];
                    }
                } else {
                    $resultado = "DONT SPECIFY ListId";
                    return ['msg' => $resultado,  'success' => false];
                }
            } else {
                $resultado = "DONT SPECIFY EMAIL";
                return ['msg' => $resultado,  'success' => false];
            }
        } else {
            $resultado = "ERROR CONFIG";
        }
    }
}

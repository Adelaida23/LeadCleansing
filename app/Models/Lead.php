<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    use HasFactory;
    //protected $guarded = [];

    /*
    public static function listEmailsVerify()
    {
        $leads_list = DB::table('leads')->orderByDesc('created_at')
            ->limit(5)->get(['id', 'email', 'status', 'reason', 'emailoversight_check', 'domain']); //'domain_country'
        return $leads_list;
    }
    */
    public static function emailsLeadCheckSinceMonths()
    {
        $fecha_atras = date('Y-m-d H:i:s', strtotime('d' . '-1 month')); //restar 3 en caso de 3 meses atras
        //$fecha_atras = date('Y-m-d ', strtotime('d' . '-1 month')); //restar 3 en caso de 3 meses atras
        //de fecha_atras toma todos los registros ordenalos descendente y toma 500 
        $leads_check_list = DB::table('leads')
            ->where('last_checked', '<=', $fecha_atras)
            ->orderByDesc('last_checked')
            ->limit(2)->get(['id','email', 'status']); //recuperar limit (500)
        return $leads_check_list;
    }
}

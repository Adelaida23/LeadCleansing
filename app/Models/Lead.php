<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lead extends Model
{
    use HasFactory;
    //protected $guarded = [];

   
    public static function emailsLeadCheckSinceMonths()
    {
        $fecha_atras = date('Y-m-d', strtotime('d' . '-3 month')); //restar 3 en caso de 3 meses atras
        //$fecha_atras = date('Y-m-d ', strtotime('d' . '-1 month')); //restar 3 en caso de 3 meses atras
        //de fecha_atras toma todos los registros ordenalos descendente y toma 500 
        $leads_check_list = DB::table('leads')
            ->where('last_checked', '<=', $fecha_atras)
            ->orderByDesc('last_checked')
            ->orderByDesc('id')
            ->limit(30)->get(['id','email', 'status']); //recuperar limit (500)
        return $leads_check_list;
    }
}

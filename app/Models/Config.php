<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;


    public function input(){

        //cases correspond to the key
        switch($this->key){

            case 'eokey':
            return \Form::text('eokey', $this->value,['required'=>'required','class'=>'form-control']);
            ;

            case 'eostatus':
            return $this->EOStatus();

            case 'resetoptizmo':
                return $this->Optizmo();

            case 'resetleads':
                return $this->Leads();


        }


    }


    function Optizmo(){

        return '<button name="resetoptizmo" class="btn btn-danger reset-confirm" id="reset-optizmo">Reset Optizmo Tables</button>';

    }
    function Leads(){

        return '<button name="resetleads" class="btn btn-danger reset-confirm" id="reset-optizmo">Reset Leads Tables</button>';

    }


    function EOStatus(){

        return  \Form::select('eostatus',

            ['online' => 'On Line', 'offline' => 'Off Line','emulate'=>'Emulate'],


            $this->value??'',

        ['class'=>'form-control']
        );


    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public $fillable =  [ 'id', 'code', 'user_id', 'amount', 'session', 'token', 'status_id', 'created_at'];

    public static function GetValue()
    {
        return rand(5, 30)/100 + rand(5, 30);
    }

    public static function CreateInvo($data)
    {

        try {
            $inv                =   new Invoices;
            $inv->code          =   rand(10000,99999);
            $inv->user_id       =   $data['user'];
            $inv->amount        =   $data['amount'];
            $inv->session       =   $data['session'];
            $inv->token         =   $data['token'];
            $inv->status_id     =   1;
            return ( ($inv->save()) ? true : false);
            
        } catch (\Exception $e) {
           return $e->getMessage();
        }

    }

    public static function ValidateInfo($iData)
    {
        return ( (isset($iData['token'])) && (isset($iData['session'])) ) ? true : false;
    }

    public static function GetInvo($info)
    {

        $invo   =   Invoices::where([
            ['token',       '=', $info['token']],
            ['session',     '=', $info['session']],
            ['status_id',   '=', 1]
        ])->get();

        return (COUNT($invo) == 1) ? $invo : false;
    }

    public static function UpdateInvo($info)
    {
        $invo   =   Invoices::where('code', '=', $info)->get();

        $invo[0]->status_id    =   2;
        return ($invo[0]->save()) ? true : false;
    }



}

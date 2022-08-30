<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Model;

use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;

class User extends Model implements Wallet, WalletFloat
{
    use HasApiTokens, HasFactory, Notifiable, HasWallet,  HasWallets, HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $fillable =  [ 'id', 'name', 'email', 'password', 'celular', 'documento'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function createData($res)
    {
        try {
            $profile    =   date('YmdHms');
            $user   =   new User;
            $user->name         =   $res['nombre'];
            $user->email        =   $res['email'];
            $user->celular      =   $res['celular'];
            $user->profile      =   $profile;
            $user->documento    =   $res['documento'];
            $user->password     =   bcrypt('User.01');
            
            if( $user->save() )
            {
                $user->createWallet([
                    'name'          =>  $user->profile,
                    'slug'          =>  $user->profile,
                    'meta'          =>  'USD',
                    'description'   =>  'Wallet Client: '.$user->name.'',
                ]);

                return true;

            }else{
                false;
            }
            
        } catch (\Exception $e) {
           return false;
        }
    }

    public static function validateAll($res)
    {
        $nom    = ( isset($res['nombre']) )     ? true : false;
        $ema    = ( isset($res['email']) )      ? true : false;
        $cel    = ( isset($res['celular']) )    ? ( (is_numeric($res['celular']) ? true : false) ) : false;
        $doc    = ( isset($res['documento']) )  ? true : false;

        if ( ($nom == true) && ($ema == true) && ($cel == true) && ($doc == true) )
        {
            return true;
        }else{
            return false;
        }

    }

    public static function validateRecharge($res)
    {
        $doc    = ( isset($res['documento']) ) ? true : false;
        $cel    = ( isset($res['celular']) )  ? ( (is_numeric($res['celular']) ? true : false) ) : false;

        if ( ($doc == true) && ($cel == true) )
        {
            return true;
        }else{
            return false;
        }

    }

    public static function validateAmount($res)
    {
        return ( isset($res['valor']) )  ? ( (is_numeric($res['valor']) ? (( $res['valor'] > 0 ) ? true : false) : false) ) : false;
    }    



    public static function validatePhone($res)
    {
        if(isset($res['celular']) == true)
        {
            return (is_numeric($res['celular'])) ? true : false;
        }else{
            return false;
        }
    }

    public static function ValidateUser($info)
    {

        $info   =   User::where([
            ['documento',   '=', $info['documento']],
            ['celular',     '=', $info['celular']]
        ])->get();

        return (COUNT($info) == 0) ? false : $info;
    }

    public static function UserByID($res)
    {
        $info   =   User::where('id', '=', $res)->get();
        return (COUNT($info) == 0) ? false : $info;
    }
}

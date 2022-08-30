<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Invoices;

class ApiController extends Controller
{
    public static function register(Request $request)
    {

        if($request->getContent() != '')
        {

            $validation     =   User::validateAll($request->request->all());

            if($validation)
            {
                
                $user       =   User::ValidateUser($request->request->all());

                if($user == false)
                {

                    $insert     =   User::createData($request->request->all());
                    
                    if($insert == true)
                    {
                        return \response()->json([
                            'status'    =>  true,
                            'message'   =>  'Usuario creado correctamente',
                        ], Response::HTTP_OK);

                    }else{

                        return \response()->json([
                            'status'    =>  false,
                            'message'   =>  'Error al crear usuario',
                        ], 400);
                    }
                
                }else{

                    return \response()->json([
                        'status'    =>  false,
                        'message'   =>  'Cliente se encuentra registrado en sistema',
                    ], Response::HTTP_OK);
                }

            
            }else{

                return \response()->json([
                    'status'    =>  false,
                    'message'   =>  'Debe enviar toda la informacion solicitada o datos correctos',
                ], Response::HTTP_OK);
            }

        }else{

            return \response()->json([
                'status'    =>  false,
                'message'   =>  'Debe enviar toda la informacion solicitada',
            ], 400);
        
        }

    }

    public function consult(Request $request)
    {

        if($request->getContent() != '')
        {
            $validation     =   User::validateRecharge($request->request->all());

            if($validation == true)
            {

                $user       =   User::ValidateUser($request->request->all());

                if( $user != false)
                {
                    $user       =   User::UserByID($user[0]->id);

                    $myWallet   =   $user[0]->getWallet($user[0]->profile);
                    $balance    =   $myWallet->balanceFloat;

                    return \response()->json([
                        'status'    =>  true,
                        'message'   =>  'El balance de su Billetera es: '.$balance.''
                    ], Response::HTTP_OK); 

                }else{
                    return \response()->json([
                        'status'    =>  false,
                        'message'   =>  'Datos de usuario invalidos o no existen.',
                    ], Response::HTTP_OK);                    
                }

            }else{
                return \response()->json([
                    'status'    =>  false,
                    'message'   =>  'Campos requeridos Invalidos, intente nuevamente',
                ], Response::HTTP_OK);
            }

        }else{

            return \response()->json([
                'status'    =>  false,
                'message'   =>  'Debe enviar toda la informacion solicitada',
            ], Response::HTTP_OK);
        
        }

    }

    public function recharge(Request $request)
    {

        if($request->getContent() != '')
        {
            $validation     =   User::validateRecharge($request->request->all());

            if($validation == true)
            {
                $valAmount  =   User::validateAmount($request->request->all());

                if($valAmount == true)
                {
                    $user       =   User::ValidateUser($request->request->all());

                    if($user != false)
                    {
                        $myWallet   =   $user[0]->getWallet($user[0]->profile);
                        $myWallet->depositFloat($request->request->all()['valor']);

                        $myWallet   =   $user[0]->getWallet($user[0]->profile);

                        return \response()->json([
                            'status'    =>  true,
                            'message'   =>  'El Balance de Billetera es: '.$myWallet->balanceFloat,
                        ], Response::HTTP_OK); 

                    }else{
                        return \response()->json([
                            'status'    =>  false,
                            'message'   =>  'Informacion de cliente no encontrado, intente nuevamente',
                        ], Response::HTTP_OK);                    
                    }
                }else{
                    return \response()->json([
                        'status'    =>  false,
                        'message'   =>  'El valor de la recarga debe ser mayor a 0, intente nuevamente',
                    ], Response::HTTP_OK);  
                }

            }else{
                return \response()->json([
                    'status'    =>  false,
                    'message'   =>  'Campos inexistente o contiene valores diferentes a numericos',
                ], Response::HTTP_OK);
            }

        }else{

            return \response()->json([
                'status'    =>  false,
                'message'   =>  'Debe enviar toda la informacion solicitada',
            ], Response::HTTP_OK);
        
        }

    }

    public function payment(Request $request)
    {

        if($request->getContent() != '')
        {
            $validation     =   User::validateRecharge($request->request->all());

            if($validation == true)
            {
                $user       =   User::ValidateUser($request->request->all());

                if($user != false)
                {
                    $value      =   Invoices::GetValue();

                    $myWallet   =   $user[0]->getWallet($user[0]->profile);
                    $balance    =   $myWallet->balanceFloat;

                    if($balance >= $value)
                    {
                        $token  =   bin2hex(random_bytes(6));
                        $id     =   bin2hex(random_bytes(6));
                        $iData  =   [
                            'user'      =>  $user[0]->id,
                            'amount'    =>  $value,
                            'session'   =>  $id,
                            'token'     =>  $token
                        ];

                        $invoices   =   Invoices::CreateInvo($iData);

                        if($invoices == true)
                        {
                            $email  =   \Mail::to($user[0]->email)->send(new \App\Mail\SendInvoice(['token' => $token, 'id' => $id]));


                            return \response()->json([
                                'status'    =>  true,
                                'message'   =>  'Se ha enviado un email con la informacion para su confirmacion de compra.'
                            ], Response::HTTP_OK); 

                        }else{
                            return \response()->json([
                                'status'    =>  false,
                                'message'   =>  'Error el crear la compra, intente nuevamente'
                            ], Response::HTTP_OK); 
                        
                        }

                    }else{
                        return \response()->json([
                            'status'    =>  false,
                            'message'   =>  'Fondo insuficiente, el balance de su Billetera es: '.$myWallet->balanceFloat.' factura: '.$value.''
                        ], Response::HTTP_OK); 
                    }

                }else{
                    return \response()->json([
                        'status'    =>  false,
                        'message'   =>  'Datos de usuario invalidos o no existen.',
                    ], Response::HTTP_OK);                    
                }

            }else{
                return \response()->json([
                    'status'    =>  false,
                    'message'   =>  'Campo inexistente o contiene valores diferentes a los requeridos',
                ], Response::HTTP_OK);
            
            }

        }else{

            return \response()->json([
                'status'    =>  false,
                'message'   =>  'Debe enviar toda la informacion solicitada',
            ], Response::HTTP_OK);
        
        }

    }
}

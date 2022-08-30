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
}

<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\DepositoUsuarios;
use App\Util\Util;

class DepositoUsuariosController extends Controller
{
    public function index($idDep, Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $depositoUsuario = DepositoUsuarios::index($idDep, $top);

        return response()->json($depositoUsuario, 200);
    }

    /*
    * Busca os Usuários que ainda não estão na Vinculados ao Depósito
    */
    public function getUsers($idDep){
        $depositoUsuario = DepositoUsuarios::getUsers($idDep);
        $columns         = DepositoUsuarios::getTableInfo();

        if(!$depositoUsuario){
            return response()->json($depositoUsuario, 200);
        }

        $depositoUsuario = Util::toSelectKeys($depositoUsuario , 'name', 'id');

        $response = [
            'depositoUsuario' => $depositoUsuario,
            'columns'   => $columns,
        ];
        return response()->json($response, 200);
    }

    public function store($idDep, Request $request){
        $depositoUsuario = $this->serializeRequest($request);

        $response = DepositoUsuarios::store($idDep, $depositoUsuario);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'depositoUsuarios' => ['Usuário x Depósito já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $depositoUsuario = Util::booleanToVarchar($requestArray);

        return $depositoUsuario;
    }   


    public function delete($idDep, $idUsu){
        $response = DepositoUsuarios::deleteItem($idDep, $idUsu);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'depositoUsuarios' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'depositoUsuarios' => ['Não foi possível excluir o Usuário do Depósito!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'idUsuario'  => ['required'],
            'movEntrada' => ['required'],
            'movSaida'   => ['required']
        ]);

        return true;
    }

    public function getById($idDep, $idUsu){
        $depositoUsuarios = DepositoUsuarios::getById($idDep, $idUsu);
        
        $customVars = [
            'true' => [
                'S'
            ],
            'false' => [
                'N'
            ]
        ];

        $depositoUsuarios = Util::varcharToBoolean(Util::toArray($depositoUsuarios), true, $customVars);

        return response()->json($depositoUsuarios, 200);
    }  
    
    public function updateItem($idDep, $idUsu, Request $request){
        $depositoUsuarios = $this->serializeRequest($request);

        $response = DepositoUsuarios::updateItem($idDep, $idUsu, $depositoUsuarios);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'depositoUsuarios' => ['Não foi possível alterar o vínculo!']
                ]
            ];
        }
        return response()->json($response, $httpCode);
    }    
   
}

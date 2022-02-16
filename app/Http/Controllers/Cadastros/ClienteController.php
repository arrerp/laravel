<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class ClienteController extends Controller
{
    public function getById($id){
        $cliente = Cliente::getById($id);

        if(!$cliente){
            return response()->json($cliente, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $cliente = Util::varcharToBoolean(Util::toArray($cliente), true, $customVars);

        return response()->json($cliente, 200);
    }

    public function store($id, Request $request){
        $cliente = $this->serializeRequest($request);

        $response = Cliente::store($id, $cliente);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'cliente' => ['Cliente já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Cliente::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'cliente' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'cliente' => ['Não foi possível excluir o Cliente!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $cliente = $this->serializeRequest($request);

        $response = Cliente::updateItem($id, $cliente);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'cliente' => ['Não foi possível alterar o Cliente!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $cliente = Util::booleanToVarchar($requestArray);

        if($cliente->permiteEmail === 'S'){
            $cliente->permiteEmail = 'S';
        } else {
            $cliente->permiteEmail = 'N';
        }

        return $cliente;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'permiteEmail'    => ['required'],
            'permiteSms'      => ['required'],
            'permiteWhats'    => ['required'],
            'restricaoVenda'  => ['required'],
            'consumidorFinal' => ['required'],
            'chargeBack'      => ['required']
        ]);


        return true;
    }
}

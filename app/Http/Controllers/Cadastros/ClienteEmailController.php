<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\ClienteEmail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class ClienteEmailController extends Controller
{
    public function getById($id){
        $clienteEmail = ClienteEmail::getById($id);

        if(!$clienteEmail){
            return response()->json($clienteEmail, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X'
            ]
        ];

        $clienteEmail = Util::varcharToBoolean(Util::toArray($clienteEmail), true, $customVars);

        return response()->json($clienteEmail, 200);
    }

    public function store($id, Request $request){
        $clienteEmail = $this->serializeRequest($request);

        $response = ClienteEmail::store($id, $clienteEmail);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'clienteEmail' => ['Email já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = ClienteEmail::deleteItem($id);

        if(!$response){
            $response = [
                'errors' => [
                    'clienteEmail' => ['Não foi possível excluir o Email!']
                ]
            ];
        }

        return response()->json($response, 200);
    }

    public function update($id, Request $request){
        $clienteEmail = $this->serializeRequest($request);

        $response = ClienteEmail::updateItem($id, $clienteEmail);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'clienteEmail' => ['Não foi possível alterar o Email!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'email'      => ['required'], 
            'id_tpEmail' => ['required'] 
        ]);


        return true;
    }
}

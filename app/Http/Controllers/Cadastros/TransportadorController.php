<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\Transportador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class TransportadorController extends Controller
{
    public function getPageInfo(Request $request){
        $columns  = Transportador::getTableInfo();

        $response = [
            'columns'  => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $transportador = Transportador::getById($id);

        if(!$transportador){
            return response()->json($transportador, 200);
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

        $transportador = Util::varcharToBoolean(Util::toArray($transportador), true, $customVars);

        $response = [
            'transportador' => $transportador
        ];        
        return response()->json($response, 200);
    }

    public function store($id, Request $request){
        $transportador = $this->serializeRequest($request);

        $response = Transportador::store($id, $transportador);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'transportador' => ['Transportador já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Transportador::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'transportador' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'transportador' => ['Não foi possível excluir o Transportador!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $transportador = $this->serializeRequest($request);

        $response = Transportador::updateItem($id, $transportador);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'transportador' => ['Não foi possível alterar o Transportador!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $transportador = Util::booleanToVarchar($requestArray);

        return $transportador;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'transpEcommerce' => ['required'],
            'cotacaoOnline'   => ['required'],
            'diaPgtoFat'      => ['required'] 
        ]);

        return true;
    }
}

<?php

namespace App\Http\Controllers\Financeiro;

use App\Models\Financeiro\Portador;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class PortadorController extends Controller
{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $portadores = Portador::index($top);

        return response()->json($portadores, 200);
    }

    public function getPageInfo(Request $request){
        $columns = Portador::getTableInfo();

        $response = [
            'columns'         => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $portadores = Portador::getById($id);

        if(!$portadores){
            return response()->json($portadores, 200);
        }
        
        $customVars = [
            'true' => [
                'S'
            ],
            'false' => [
                'X',
                'N'
            ]
        ];

        $portadores = Util::varcharToBoolean(Util::toArray($portadores), true, $customVars);

        $response = [
            'portador' => $portadores
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $portadores = $this->serializeRequest($request);

        $response = Portador::store($portadores);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'portador' => ['Portador já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Portador::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'portador' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'portador' => ['Não foi possível excluir o Portador!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $portador = $this->serializeRequest($request);

        $response = Portador::updateItem($id, $portador);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'portador' => ['Não foi possível alterar o Portador!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $portador = Util::booleanToVarchar($requestArray);

        if($portador->situacao === 'S'){
            $portador->situacao = 'A';
            
        } else {
            $portador->situacao = 'X';
        }


        return $portador;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'descricao'  => ['required'],
            'prDesconto' => ['required'],
            'vlDesconto' => ['required'],
            'situacao'   => ['required']
        ]);

        return true;
    }
}
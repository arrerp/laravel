<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\Parceiro;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class ParceiroController extends Controller
{
    public function getById($id){
        $parceiro = Parceiro::getById($id);
        $columns  = Parceiro::getTableInfo();
        
        if(!$parceiro){
            return response()->json($parceiro, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X'
            ]
        ];

        $parceiro = Util::varcharToBoolean(Util::toArray($parceiro), true, $customVars);

        $response = [
            'parceiro'  => $parceiro,
            'columns'  => $columns,
        ];

        return response()->json($response, 200);
    }

    public function store($id, Request $request){
        $parceiro = $this->serializeRequest($request);

        $response = Parceiro::store($id, $parceiro);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'parceiro' => ['Parceiro já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Parceiro::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Parceiro::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'parceiro' => ['Não foi possível excluir o Parceiro!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }   

    public function update($id, Request $request){
        $parceiro = $this->serializeRequest($request);

        $response = Parceiro::updateItem($id, $parceiro);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'parceiro' => ['Não foi possível alterar o Parceiro!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $parceiro = Util::booleanToVarchar($requestArray);


        if($parceiro->situacao === 'S'){
            $parceiro->situacao = 'A';
        } else {
            $parceiro->situacao = 'X';
        }

        return $parceiro;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'situacao'      => ['required']
        ]);


        return true;
    }
}

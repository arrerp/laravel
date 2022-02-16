<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\Empresa;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class EmpresaController extends Controller
{
    public function getById($id){
        $empresa = Empresa::getById($id);
        $columns = Empresa::getTableInfo();

        if(!$empresa){
            return response()->json($empresa, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X'
            ]
        ];

        $empresa = Util::varcharToBoolean(Util::toArray($empresa), true, $customVars);

        $response = [
            'empresa' => $empresa,
            'columns' => $columns,
        ];

        return response()->json($response, 200);
    }

    public function store($id, Request $request){
        $empresa = $this->serializeRequest($request);

        $response = Empresa::store($id, $empresa);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'empresa' => ['Empresa já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Empresa::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Empresa::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'empresa' => ['Não foi possível excluir a Empresa!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }      

    public function update($id, Request $request){
        $empresa = $this->serializeRequest($request);

        $response = Empresa::updateItem($id, $empresa);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'empresa' => ['Não foi possível alterar a Empresa!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $empresa = Util::booleanToVarchar($requestArray);

        return $empresa;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'empFilial' => ['required'], 
            'situacao'  => ['required'] 
        ]);

        return true;
    }

}

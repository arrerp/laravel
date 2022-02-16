<?php

namespace App\Http\Controllers\Logistica;

use App\Models\Logistica\EtiquetaTransporte;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class EtiquetaTransporteController extends Controller
{
    public function getPageInfo(Request $request){
        $columns = EtiquetaTransporte::getTableInfo();

        $response = [
            'columns' => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $etiquetaTransporte = EtiquetaTransporte::getById($id);

        if(!$etiquetaTransporte){
            return response()->json($etiquetaTransporte, 200);
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

        $etiquetaTransporte = Util::varcharToBoolean(Util::toArray($etiquetaTransporte), true, $customVars);

        $response = [
            'etiquetaTransporte' => $etiquetaTransporte
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $etiquetaTransporte = $this->serializeRequest($request);

        $response = EtiquetaTransporte::store($etiquetaTransporte);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'etiquetaTransporte' => ['Etiqueta Transporte já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = EtiquetaTransporte::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'etiquetaTransporte' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'etiquetaTransporte' => ['Não foi possível excluir a Etiqueta de Transporte!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $etiquetaTransporte = $this->serializeRequest($request);

        $response = EtiquetaTransporte::updateItem($id, $etiquetaTransporte);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'etiquetaTransporte' => ['Não foi possível alterar a Etiqueta Transporte!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $etiquetaTransporte = Util::booleanToVarchar($requestArray);

        return $etiquetaTransporte;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'descricao'       => ['required'],
            'situacao'        => ['required']
        ]);

        return true;
    }
}
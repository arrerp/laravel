<?php

namespace App\Http\Controllers\Logistica;

use App\Models\Logistica\TipoTransporte;
use App\Models\Cadastros\Transportador;
use App\Models\Logistica\EtiquetaTransporte;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class TipoTransporteController extends Controller
{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $tipoTransporte = TipoTransporte::index($top);

        return response()->json($tipoTransporte, 200);
    }

    public function getPageInfo(Request $request){
        $etiquetas       = EtiquetaTransporte::index();
        $transportadores = Transportador::index();
        $columns         = TipoTransporte::getTableInfo();

        $response = [
            'etiquetas'       => Util::toSelectKeys($etiquetas , 'descricao', 'id_etiqueta' ),
            'transportadores' => Util::toSelectKeys($transportadores , 'fantasia', 'id_cadastro' ),
            'columns'         => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $tipoTransporte = TipoTransporte::getById($id);

        if(!$tipoTransporte){
            return response()->json($tipoTransporte, 200);
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

        $tipoTransporte = Util::varcharToBoolean(Util::toArray($tipoTransporte), true, $customVars);

        $response = [
            'tipoTransporte' => $tipoTransporte
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $tipoTransporte = $this->serializeRequest($request);

        $response = TipoTransporte::store($tipoTransporte);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'tipoTransporte' => ['Tipo Transporte já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = TipoTransporte::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'tipoTransporte' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'tipoTransporte' => ['Não foi possível excluir o Tipo de Transporte!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $tipoTransporte = $this->serializeRequest($request);

        $response = TipoTransporte::updateItem($id, $tipoTransporte);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'tipoTransporte' => ['Não foi possível alterar o Tipo Transporte!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $transportador = Util::booleanToVarchar($requestArray);

        if($transportador->situacao === 'S'){
            $transportador->situacao = 'A';
            
        } else {
            $transportador->situacao = 'X';
        }


        return $transportador;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'idTransportador' => ['required'],
            'descricao'       => ['required'],
            'idEtiqueta'      => ['required'],
            'validoEcommerce' => ['required'],
            'validoErp'       => ['required'],
            'situacao'        => ['required']
        ]);

        return true;
    }
}
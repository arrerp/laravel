<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\TipoMovimento;
use App\Util\Util;

class TipoMovimentoController extends Controller
{
    public function index(){
        $top = 10000;

        $tipoMovimento = TipoMovimento::index($top);
        return response()->json($tipoMovimento, 200);
    }

    public function getById($id){
        $tipoMovimento = TipoMovimento::getById($id);
        $columns = TipoMovimento::getTableInfo();
        
        $customVars = [
            'true' => [
                'S'
            ],
            'false' => [
                'N'
            ]
        ];

        $tipoMovimento = Util::varcharToBoolean(Util::toArray($tipoMovimento), true, $customVars);

        $response = [
            'tipoMovimento' => $tipoMovimento,
            'columns' => $columns,
        ];

        return response()->json($response, 200);        
    }

    public function store(Request $request){
        $tipoMovimento = $this->serializeRequest($request);

        $response = TipoMovimento::store($tipoMovimento);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'tipoMovimento' => ['Tipo de Movimento já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = TipoMovimento::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'tipoMovimento' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'tipoMovimento' => ['Não foi possível excluir o Tipo de Movimento!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function update($id, Request $request){
        $tipoMovimento = $this->serializeRequest($request);

        $response = TipoMovimento::updateItem($id, $tipoMovimento);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'tipoMovimento' => ['Não foi possível alterar o Tipo de Movimento!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }


    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $tipoMovimento = Util::booleanToVarchar($requestArray);

        return $tipoMovimento;
    }   

    private function requestValidate($request){
        $request->validate([
            'descricao'    => ['required'],
            'sinal'        => ['required'],
            'trafDeposito' => ['required'],
            'obrigaValor'  => ['required'],
            'obrigaObs'    => ['required']          
        ]);


        return true;
    }
}

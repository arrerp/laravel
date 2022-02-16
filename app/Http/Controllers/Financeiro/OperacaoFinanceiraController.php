<?php

namespace App\Http\Controllers\Financeiro;

use App\Models\Financeiro\OperacaoFinanceira;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class OperacaoFinanceiraController extends Controller
{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $operacao = OperacaoFinanceira::index($top);

        return response()->json($operacao, 200);
    }

    public function getPageInfo(Request $request){
        $columns = OperacaoFinanceira::getTableInfo();

        $response = [
            'columns' => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $operacao = OperacaoFinanceira::getById($id);

        if(!$operacao){
            return response()->json($operacao, 200);
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

        $operacao = Util::varcharToBoolean(Util::toArray($operacao), true, $customVars);

        $response = [
            'operacao' => $operacao
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $response = OperacaoFinanceira::store($request);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'operacao' => ['Operação já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = OperacaoFinanceira::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'operacao' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'operacao' => ['Não foi possível excluir a Operação!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $operacao = $this->serializeRequest($request);

        $response = OperacaoFinanceira::updateItem($id, $operacao);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'operacao' => ['Não foi possível alterar a Operação!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'descricao'       => ['required'],
            'especie'         => ['required'],
            'id_aplicacao'    => ['required'],
            'id_operacaoEst'  => ['required'],
            'sinal'           => ['required'],
            'id_funcaoFinanc' => ['required'],
        ]);

        return true;
    }
}
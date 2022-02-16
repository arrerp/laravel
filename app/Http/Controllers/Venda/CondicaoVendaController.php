<?php

namespace App\Http\Controllers\Venda;

use App\Models\Venda\CondicaoVenda;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class CondicaoVendaController extends Controller
{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $condicaoVenda = CondicaoVenda::index($top);

        return response()->json($condicaoVenda, 200);
    }

    public function getPageInfo(Request $request){
        $columns = CondicaoVenda::getTableInfo();

        $response = [
            'columns' => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $condicaoVenda = CondicaoVenda::getById($id);

        if(!$condicaoVenda){
            return response()->json($condicaoVenda, 200);
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

        $condicaoVenda = Util::varcharToBoolean(Util::toArray($condicaoVenda), true, $customVars);

        $response = [
            'condicaoVenda' => $condicaoVenda
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $condicaoVenda = $this->serializeRequest($request); 
        $insert =  $this->GetDias($condicaoVenda); 
        $insert['descricao']   = $condicaoVenda->descricao;
        $insert['tp_prazo']    = $condicaoVenda->tpPrazo;
        $insert['geraTitulo']  = $condicaoVenda->geraTitulo;
        $insert['qt_parcelas'] = $condicaoVenda->qtParcelas;
        $insert['tp_fator']    = $condicaoVenda->tpFator;
        $insert['situacao']    = $condicaoVenda->situacao;
        $insert['icone']       = $condicaoVenda->icone;

        $response = CondicaoVenda::store($insert);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'condicaoVenda' => ['Condição de Venda já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function GetDias($payLoad){
        $dias = [];
        
        /*
        foreach ($payLoad as $key => $value){
            if (strstr($key, 'dia')) {
                $dias[$key]=$value;
            }
        }
        */
        for($i=1; $i<=24; $i++){
            $variavel  = 'dia'.$i;
            $dias[$variavel] = isset($payLoad->$variavel) ? $payLoad->$variavel:0; 
        }
        return $dias;
    }

    public function delete($id){
        $response = CondicaoVenda::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'condicaoVenda' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'condicaoVenda' => ['Não foi possível excluir a Condição de Venda!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $condicaoVenda = Util::toObject($this->serializeRequest($request)); 

        $insert = $this->GetDias($condicaoVenda); 
        $insert['descricao']   = $condicaoVenda->descricao;
        $insert['tp_prazo']    = $condicaoVenda->tpPrazo;
        $insert['geraTitulo']  = $condicaoVenda->geraTitulo;
        $insert['qt_parcelas'] = $condicaoVenda->qtParcelas;
        $insert['tp_fator']    = $condicaoVenda->tpFator;
        $insert['situacao']    = $condicaoVenda->situacao;
        $insert['icone']       = $condicaoVenda->icone;

        $response = CondicaoVenda::updateItem($id, $insert);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'condicaoVenda' => ['Não foi possível alterar a Condição de Venda!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $condicaoVenda = Util::booleanToVarchar($requestArray);

        if($condicaoVenda->situacao === 'N'){
            $condicaoVenda->situacao = 'X';
        }
        if($condicaoVenda->situacao === 'S'){
            $condicaoVenda->situacao = 'A';
        }

        return $condicaoVenda;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'descricao'   => ['required'],
            'tpPrazo'     => ['required'],
            'geraTitulo'  => ['required'],
            'qtParcelas'  => ['required'],
            'dia1'        => ['required'],
            'tpFator'     => ['required'],
            'situacao'    => ['required']
        ]);

        return true;
    }
}
<?php

namespace App\Http\Controllers\Materiais;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\TabelaPreco;
use App\Models\Cadastros\Empresa;
use App\Util\Util;

class TabelaPrecoController extends Controller
{
    public function index($id){
        if ($id == "null"){
            $id = null;
        } 

        $top = 10000;

        $tabPreco = TabelaPreco::index($top, $id);
        return response()->json($tabPreco, 200);
    }

    public function getById($id){
        $tabPreco = TabelaPreco::getById($id);
        $columns  = TabelaPreco::getTableInfo();

        if(!$tabPreco){
            return response()->json($tabPreco, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X'
            ]
        ];

        $tabPreco = Util::varcharToBoolean(Util::toArray($tabPreco), true, $customVars);

        $response = [
            'tabPreco' => $tabPreco,
            'columns'  => $columns
        ];
        return response()->json($response, 200);
    }

    public function getPageInfo(Request $request){
        $empresas = Empresa::index();
        $columns  = TabelaPreco::getTableInfo();

        $response = [
            'empresas' => Util::toSelectKeys($empresas , 'fantasia', 'id_empresa' ),
            'columns'  => $columns
        ];

        return response()->json($response, 200);        
    }        

    public function store($id, Request $request){
        $tabPreco = $this->serializeRequest($request);

        $response = TabelaPreco::store($id, $tabPreco);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'tabelaPreco' => ['Tabela Preço já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = TabelaPreco::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = TabelaPreco::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'tabelaPreco' => ['Não foi possível excluir a Tabela de Preço!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function update($id, Request $request){
        $tabPreco = $this->serializeRequest($request);

        $response = TabelaPreco::updateItem($id, $tabPreco);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'tabelaPreco' => ['Não foi possível alterar a Tabela de Preço!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $tabPreco = Util::booleanToVarchar($requestArray);


        if($tabPreco->situacao === 'S'){
            $tabPreco->situacao = 'A';
        } else {
            $tabPreco->situacao = 'X';
        }

        return $tabPreco;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'descricao' => ['required'],
            'situacao'  => ['required']
        ]);


        return true;
    }
}

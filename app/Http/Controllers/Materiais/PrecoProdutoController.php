<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\PrecoProduto;
use App\Models\Materiais\Produto;
use App\Util\Util;

class PrecoProdutoController extends Controller
{
    public function getPageInfo($idTab, Request $request){
        $produtos = Produto::index();
        $columns  = PrecoProduto::getTableInfo();

        $response = [
            'produtos' => Util::toSelectKeys($produtos , 'descricao', 'id_produto'),
            'columns'  => $columns
        ];

        return response()->json($response, 200);        
    }

    public function index($id){
        if ($id == "null"){
            $id = null;
        } 

        $top = 3;

        $precoProduto = PrecoProduto::index($top, $id);
        return response()->json($precoProduto, 200);
    }

    public function getById($id){
        $precoProduto = PrecoProduto::getById($id);
        $columns = PrecoProduto::getTableInfo();

        if(!$precoProduto){
            return response()->json($precoProduto, 200);
        }
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X'
            ]
        ];

        $precoProduto = Util::varcharToBoolean(Util::toArray($precoProduto), true, $customVars);

        $response = [
            'precoProduto' => $precoProduto,
            'columns'      => $columns
        ];
        return response()->json($response, 200);
    }

    public function store($id, Request $request){
        $precoProduto = $this->serializeRequest($request);

        $response = PrecoProduto::store($id, $precoProduto);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'precoProduto' => ['Preço já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = PrecoProduto::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = PrecoProduto::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'precoProduto' => ['Não foi possível excluir o Preço do Produto!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function update($id, Request $request){
        $precoProduto = $this->serializeRequest($request);

        $response = PrecoProduto::updateItem($id, $precoProduto);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'precoProduto' => ['Não foi possível alterar o Preço!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $precoProduto = Util::booleanToVarchar($requestArray);


        if($precoProduto->situacao === 'S'){
            $precoProduto->situacao = 'A';
        } else {
            $precoProduto->situacao = 'X';
        }

        return $precoProduto;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'vlUnitario' => ['required'],
            'situacao'   => ['required']
        ]);


        return true;
    }

    public function getByTabId($id){
        $precoProduto = PrecoProduto::getByTabId($id);
        $columns  = PrecoProduto::getTableInfo();

        if(!$precoProduto){
            return response()->json($precoProduto, 200);
        }

        $precoProduto = Util::toSelectKeys($precoProduto , 'descricao', 'id_produto');

        $response = [
            'precoProduto' => $precoProduto,
            'columns'      => $columns
        ];
        return response()->json($response, 200);

    }

}

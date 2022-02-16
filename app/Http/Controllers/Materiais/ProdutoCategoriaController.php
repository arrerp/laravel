<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use App\Models\Materiais\Produto;
use Illuminate\Http\Request;
use App\Models\Materiais\ProdutoCategoria;
use App\Util\Util;

class ProdutoCategoriaController extends Controller
{
    public function index(Request $request){
        $top = 1000;

        if($request->top){
            $top = $request->top;
        }

        $categorias = ProdutoCategoria::index($top);

        return response()->json($categorias, 200);
    }

    public function getPageInfo(Request $request){
        $columns = ProdutoCategoria::getTableInfo();

        if($request->id){
            $categorias = ProdutoCategoria::getWhereNot($request->id);
        
        } else {
            $categorias = ProdutoCategoria::index();
        }

        $response = [
            'categorias' => Util::toSelectKeys($categorias, 'categoria', 'id_categoria'),
            'columns'    => $columns,
        ];

        return response()->json($response, 200);
    }

    public function getById($id){
        $categoria = ProdutoCategoria::getById($id);

        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $categoria = Util::varcharToBoolean(Util::toArray($categoria), true, $customVars);

        return response()->json($categoria, 200);
    }

    public function delete($id){
        $response = ProdutoCategoria::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = ProdutoCategoria::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'categoria' => ['Não foi possível excluir a Categoria!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function store(Request $request){
        $httpCode = 200;

        $request = $this->requestValidate($request);

        $response = ProdutoCategoria::store($request);

        if(!$response){
            $response = [
                'errors' => [
                    'categoria' => ['Categoria já cadastrada']
                ]
            ];

            $httpCode = 402;
        
        } else {
            $response = [
                'message' => 'Categoria cadastrada com sucesso'
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function update($id, Request $request){
        $categoria = $this->serializeRequest($request);

        $response = ProdutoCategoria::updateItem($id, $categoria);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'categoria' => ['Não foi possível alterar a categoria!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'categoria' => ['required'],
            'situacao' => ['required'],
        ]);

        $categoria = $this->serializeRequest($request);

        return $categoria;
    }

    private function serializeRequest($request){
        $requestArray = json_decode($request->getContent(), true);

        $produto = Util::booleanToVarchar($requestArray);

        if($produto->situacao === 'S'){
            $produto->situacao = 'A';
        
        } else {
            $produto->situacao = 'X';
        }

        return $produto;
    }

}

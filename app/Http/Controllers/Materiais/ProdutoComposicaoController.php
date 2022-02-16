<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use App\Models\Materiais\Produto;
use Illuminate\Http\Request;
use App\Models\Materiais\ProdutoComposicao;
use App\Util\Util;

class ProdutoComposicaoController extends Controller
{
    public function index(Request $request){
        $top = 1000;

        if($request->top){
            $top = $request->top;
        }

        $composicoes = ProdutoComposicao::index($top);

        return response()->json($composicoes, 200);
    }

    public function getById($id){
        $composicao = ProdutoComposicao::getById($id);

        return response()->json($composicao, 200);
    }

    public function delete($idProdutoOri, $idProdutoDes){
        $delete = ProdutoComposicao::deleteItem($idProdutoOri, $idProdutoDes);

        return response()->json($delete, 200);
    }

    public function store(Request $request){
        $httpCode = 200;

        $request = $this->requestValidate($request);

        $response = ProdutoComposicao::store($request);

        if(!$response){
            $response = [
                'errors' => [
                    'composicao' => ['Composição já cadastrada']
                ]
            ];

            $httpCode = 402;
        
        } else {
            $response = [
                'message' => 'Composição cadastrada com sucesso'
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'idProdutoOri' => ['required'],
            'idProdutoDes' => ['required'],
            'qtAplicada'   => ['required'],
        ]);

        $categoria = $this->serializeRequest($request);

        return $categoria;
    }
}

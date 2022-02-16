<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\ProdutoFamilia;

class ProdutoFamiliaController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $familias = ProdutoFamilia::index($top);

        return response()->json($familias, 200);
    }

    public function store(Request $request){
        $request->validate([
            'familia' => ['required']
        ]);

        $response = ProdutoFamilia::store($request->familia);

        if(!$response){
            $response = [
                'errors' => [
                    'familia' => ['Família do produto já cadastrada']
                ]
            ];

            return response()->json($response, 401);
        
        }


        return response()->json($response, 200);
    }

    public function delete($id){
        $response = ProdutoFamilia::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'familia' => ['Não foi possível excluir a Família! Registro referenciado ao Produto.']
                    ]   
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'familia' => ['Não foi possível excluir a Família!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function update($id, Request $request){
        $response = ProdutoFamilia::updateItem($id, $request->familia);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'familia' => ['Não foi possível alterar a família do produto']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }
}

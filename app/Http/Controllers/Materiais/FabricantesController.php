<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\ProdutoFabricante;

class FabricantesController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $fabricantes = ProdutoFabricante::index($top);

        return response()->json($fabricantes, 200);
    }

    public function store(Request $request){
        $request->validate([
            'fabricante' => ['required']
        ]);

        $response = ProdutoFabricante::store($request->fabricante);

        if(!$response){
            $response = [
                'errors' => [
                    'fabricante' => ['Fabricante já cadastrado']
                ]
            ];

            return response()->json($response, 401);
        
        }


        return response()->json($response, 200);
    }

    public function delete($id){
        $response = ProdutoFabricante::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'fabricante' => ['Não foi possível excluir o Fabricante! Registro referenciado ao Produto.']
                    ]   
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'fabricante' => ['Não foi possível excluir o Fabricante!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function update($id, Request $request){
        $response = ProdutoFabricante::updateItem($id, $request->fabricante);

        if(!$response){
            $response = [
                'errors' => [
                    'delete' => ['Não foi possível alterar o fabricante']
                ]
            ];
        }

        return response()->json($response, 200);
    }
}
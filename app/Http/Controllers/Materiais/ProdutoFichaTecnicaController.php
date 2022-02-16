<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\ProdutoFichaTecnica;

class ProdutoFichaTecnicaController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $fichatecnica = ProdutoFichaTecnica::index($top);

        return response()->json($fichatecnica, 200);
    }

    public function store(Request $request){
        $request->validate([
            'fichaTecnica' => ['required']
        ]);

        $response = ProdutoFichaTecnica::store($request->fichaTecnica);

        if(!$response){
            $response = [
                'errors' => [
                    'fichaTecnica' => ['Ficha do produto já cadastrada']
                ]
            ];

            return response()->json($response, 401);
        
        }


        return response()->json($response, 200);
    }

    public function delete($id){
        $response = ProdutoFichaTecnica::deleteItem($id);

        if(!$response){
            $response = [
                'errors' => [
                    'fichaTecnica' => ['Não foi possível excluir a Ficha do produto']
                ]
            ];
        }

        return response()->json($response, 200);
    }

    public function update($id, Request $request){
        $response = ProdutoFichaTecnica::updateItem($id, $request->fichaTecnica);

        if(!$response){
            $response = [
                'errors' => [
                    'fichaTecnica' => ['Não foi possível alterar a Ficha do produto']
                ]
            ];
        }

        return response()->json($response, 200);
    }
}

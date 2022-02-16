<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\FichaTecnicaValor;

class FichaTecnicaValorController extends Controller
{
    public function index($id_fichaTecnica){
        //return response()->json($id_fichaTecnica, 200);

        $fichaValor = FichaTecnicaValor::index($id_fichaTecnica);

        return response()->json($fichaValor, 200);
    }

    public function store(Request $request){
        $request->validate([
            'id_fichaTecnica' => ['required'],
            'valor' => ['required']
        ]);

        $response = FichaTecnicaValor::store($request);

        if(!$response){
            $response = [
                'errors' => [
                    'fichaValor' => ['Valor da Ficha já cadastrada']
                ]
            ];

            return response()->json($response, 401);
        
        }


        return response()->json($response, 200);
    }

    public function delete($id){
        $response = FichaTecnicaValor::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = FichaTecnicaValor::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'fichaValor' => ['Não foi possível excluir o valor da Ficha Técnica!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  

    public function update($id, Request $request){
        $response = FichaTecnicaValor::updateItem($id, $request->fichaValor);

        if(!$response){
            $response = [
                'errors' => [
                    'fichaValor' => ['Não foi possível alterar o Valor da Ficha do produto']
                ]
            ];
        }

        return response()->json($response, 200);
    }
}

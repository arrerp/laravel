<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cadastros\TiposEmail;

class TiposEmailController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $tiposEmail = TiposEmail::index($top);

        return response()->json($tiposEmail, 200);
    }

    public function store(Request $request){
        $request->validate([
            'descricao' => ['required']
        ]);

        $response = TiposEmail::store($request->descricao);

        if(!$response){
            $response = [
                'errors' => [
                    'tiposEmail' => ['Tipo de Email já cadastrado!']
                ]
            ];

            return response()->json($response, 401);
        
        }


        return response()->json($response, 200);
    }

    public function delete($id){
        $response = TiposEmail::deleteItem($id);

        if(!$response){
            $response = [
                'errors' => [
                    'tiposEmail' => ['Não foi possível excluir a Tipo de Email!']
                ]
            ];
        }

        return response()->json($response, 200);
    }

    public function update($id, Request $request){
        $response = TiposEmail::updateItem($id, $request->descricao);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'tiposEmail' => ['Não foi possível alterar o Tipo de Email!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }
}

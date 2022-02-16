<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\FuncaoUsuario;
use App\Util\Util;

class FuncaoUsuarioController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $grupoUsuario = FuncaoUsuario::index($top);

        return response()->json($grupoUsuario, 200);
    }

    public function store(Request $request){
        $response = FuncaoUsuario::store($request);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'funcaoUsuario' => ['Função já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = FuncaoUsuario::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'funcaoUsuario' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'funcaoUsuario' => ['Não foi possível excluir a função do Usuário!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function update($id, Request $request){

        $response = FuncaoUsuario::updateItem($id, $request);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'funcaoUsuario' => ['Não foi possível alterar a função!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

}
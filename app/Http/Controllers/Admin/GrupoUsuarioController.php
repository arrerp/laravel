<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\GrupoUsuario;
use App\Util\Util;

class GrupoUsuarioController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $grupoUsuario = GrupoUsuario::index($top);

        return response()->json($grupoUsuario, 200);
    }

    public function getById($id){
        $grupoUsuario = GrupoUsuario::getById($id);
        
        return response()->json($grupoUsuario, 200);
    }

    public function store(Request $request){
        $response = GrupoUsuario::store($request);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'grupoUsuario' => ['Grupo já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = GrupoUsuario::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'grupoUsuario' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'grupoUsuario' => ['Não foi possível excluir o Grupo do Usuário!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function update($id, Request $request){
        $response = GrupoUsuario::updateItem($id, $request);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'grupoUsuario' => ['Não foi possível alterar o Grupo!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'idGrupo' => ['required'],
            'grupo'   => ['required'] 
        ]);

        return true;
    }
}
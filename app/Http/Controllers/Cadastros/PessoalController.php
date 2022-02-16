<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cadastros\Pessoal;
use App\Util\Util;

class PessoalController extends Controller
{
    public function getById($id){
        $pessoal = Pessoal::getById($id);
        $columns = Pessoal::getTableInfo();
        
        $response = [
            'pessoal'  => $pessoal,
            'columns'  => $columns,
        ];

        return response()->json($response, 200);
    }

    public function index($id, Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }
        $pessoal = Pessoal::index($id, $top);

        return response()->json($pessoal, 200);
    }

    public function store($id, Request $request){
        $response = Pessoal::store($id, $request);
        $httpCode = 200;

        return response()->json($response, 200);

        if(!$response){
            $response = [
                'errors' => [
                    'pessoal' => ['Cadastro Pessoal já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Pessoal::deleteItem($id);

        if(!$response){
            $response = [
                'errors' => [
                    'pessoal' => ['Não foi possível excluir o Cadastro!']
                ]
            ];
        }

        return response()->json($response, 200);
    }

    public function update($id, Request $request){
        $response = Pessoal::updateItem($id, $request);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'pessoal' => ['Não foi possível alterar o Cadastro Pessoal!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }
}

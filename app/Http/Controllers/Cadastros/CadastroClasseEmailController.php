<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use App\Models\Cadastros\CadastroClasseEmail;
use Illuminate\Http\Request;
use App\Util\Util;

class CadastroClasseEmailController extends Controller
{

    public function index($id)
    {
        $top = 10000;

        $cadastroclassesEmail = CadastroClasseEmail::index($id, $top);

        return response()->json($cadastroclassesEmail, 200);
    }

    public function store(Request $request)
    {

        $existe = CadastroClasseEmail::existsItem($request);

        if (!$existe) {
            $response = CadastroClasseEmail::store($request);

            $httpCode = 200;

            if (!$response) {
                $response = [
                    'errors' => [
                        'cadastro' => ['Erro ao cadastrar o email!']
                    ]
                ];
                $httpCode = 402;
            }
        } else {
            $response = [
                'errors' => [
                    'existe' => ['Email já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id)
    {
        $response = CadastroClasseEmail::deleteItem($id);
        $httpCode = 200;

        if (!$response) {
            $httpCode = 400;
            $response = [
                'errors' => [
                    'descricao' => ['Não foi possível excluir o email!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }
}

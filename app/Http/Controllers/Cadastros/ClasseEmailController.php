<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use App\Models\Cadastros\ClasseEmail;
use Illuminate\Http\Request;
use App\Util\Util;

class ClasseEmailController extends Controller
{


    public function getPageInfo(Request $request){
        $classes  = ClasseEmail::index();

        $response = [
            'classes'  => Util::toSelectKeys($classes , 'descricao', 'id_classe' )
        ];

        return response()->json($response, 200);
    }


    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $classesEmail = ClasseEmail::index($top);

        return response()->json($classesEmail, 200);
    }

    public function store(Request $request){

        $existsItem = ClasseEmail::existsItem($request->descricao);

        if (!$existsItem) {
            $response = ClasseEmail::store($request);
            $httpCode = 200;

        } else {
            $response = [
                'errors' => [
                    'descricao' => ['Classe já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }



        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = ClasseEmail::deleteItem($id);
        $httpCode = 200;

        if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'descricao' => ['Não foi possível excluir a classe!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function update($id, Request $request){

        $existsItem = ClasseEmail::existsItem($request->descricao);

        if (!$existsItem) {
            $response = ClasseEmail::updateItem($id, $request);
            $httpCode = 200;
        } else {
            $httpCode = 402;
            $response = [
                'errors' => [
                    'descricao' => ['Classe já cadastrada!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }


}

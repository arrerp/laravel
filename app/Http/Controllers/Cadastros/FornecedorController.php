<?php

namespace App\Http\Controllers\Cadastros;

use App\Models\Cadastros\Fornecedor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class FornecedorController extends Controller
{
    public function getById($id){

        $fornecedor = Fornecedor::getById($id);

        if(!$fornecedor){
            return response()->json($fornecedor, 200);
        }

        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $fornecedor = Util::varcharToBoolean(Util::toArray($fornecedor), true, $customVars);

        return response()->json($fornecedor, 200);
    }

    public function store($id, Request $request){

        $fornecedor = $this->serializeRequest($request);

        $response = Fornecedor::store($id, $fornecedor);

        $httpCode = 200;

        if(!$response){
            $response = [
                'errors' => [
                    'fornecedor' => ['Fornecedor já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($request, $httpCode);
    }

    private function serializeRequest($request){

        $requestArray = json_decode($request->getContent(), true);

        $fornecedor = Util::booleanToVarchar($requestArray);

        return $fornecedor;
    }


}

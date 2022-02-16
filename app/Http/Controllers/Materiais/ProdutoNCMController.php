<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use App\Models\Materiais\ProdutoNCM;
use Illuminate\Http\Request;
use App\Util\Util;


class ProdutoNCMController extends Controller{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $produtoNCM = ProdutoNCM::index($top);

        return response()->json($produtoNCM, 200);
    }
    
    public function store(Request $request){
        
        $produtoNCM = $this->serializeRequest($request);

        $response = ProdutoNCM::store($produtoNCM);
        
        $httpCode = 200;

        if(!$response){
            $response = [
                'errors' => [
                    'produtoNCM' => ['NCM já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){

        $response = ProdutoNCM::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = ProdutoNCM::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'descricao' => ['Não foi possível excluir o produto!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }  


    public function update($id, Request $request){

        $response = ProdutoNCM::updateItem($id, $request);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'descricao' => ['Não foi possível alterar o produto!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $produtoNCM = Util::booleanToVarchar($requestArray);

    
        if($produtoNCM->situacao === 'S'){
            $produtoNCM->situacao = 'A';
        } else {
            $produtoNCM->situacao = 'X';
        }

        return $produtoNCM;
    }
    
    private function requestValidate($request){
        $request->validate([
            'ncm'    => ['required'],
            'descricao'    => ['required']
        ]);

        return true;
    }

   

}

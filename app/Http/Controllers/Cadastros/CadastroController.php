<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cadastros\Cadastro;
use App\Models\Cadastros\Cidade;
use App\Util\Util;

class CadastroController extends Controller
{
    public function getPageInfo(Request $request){
        $cidades  = Cidade::index();
        $columns  = Cadastro::getTableInfo();

        $response = [
            'cidades'  => Util::toSelectKeys($cidades , 'cidade_uf', 'id_cidade' ),
            'columns'  => $columns,
        ];

        return response()->json($response, 200);        
    }    

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $cadastro = Cadastro::index($top);

        return response()->json($cadastro, 200);
    }

    public function getById($id){
        $cadastro = Cadastro::getById($id);
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $cadastro = Util::varcharToBoolean(Util::toArray($cadastro), true, $customVars);

        return response()->json($cadastro, 200);
    }

    public function existsCnpjCpf($cnpjCpf){
        $response = Cadastro::existsCnpjCpf($cnpjCpf);

        return response()->json($response, 200);
    }    

    public function store(Request $request){
        $cadastro = $this->serializeRequest($request);

        $response = Cadastro::store($cadastro);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'cadastro' => ['Registro já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Cadastro::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Cadastro::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'cadastro' => ['Não foi possível excluir o Cadastro!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }   

    public function update($id, Request $request){
        $cadastro = $this->serializeRequest($request);

        $response = Cadastro::updateItem($id, $cadastro);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'cadastro' => ['Não foi possível alterar o Cadastro!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $cadastro = Util::booleanToVarchar($requestArray);

        if($cadastro->situacao === 'S'){
            $cadastro->situacao = 'A';
        } else {
            $cadastro->situacao = 'X';
        }

        return $cadastro;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'razaoSocial' => ['required'],
            'fantasia'    => ['required'],
            'cnpjCpf'     => ['required'],
            'cep'         => ['required'],
            'endereco'    => ['required'],
            'bairro'      => ['required'],
            'numero'      => ['required'],
            'idCidade'    => ['required'],
            'email'       => ['required', 'email']
        ]);

        return true;
    }
}

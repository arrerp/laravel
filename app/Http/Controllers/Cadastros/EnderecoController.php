<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cadastros\Cidade;
use App\Models\Cadastros\Endereco;
use App\Models\Cadastros\EnderecoPadrao;
use App\Util\Util;

class EnderecoController extends Controller
{
    public function getPageInfo(Request $request){
        $cidades  = Cidade::index();
        $columns  = Endereco::getTableInfo();


        $response = [
            'cidades'  => Util::toSelectKeys($cidades , 'cidade_uf', 'id_cidade' ),
            'columns'  => $columns,
        ];

        return response()->json($response, 200);        
    }    

    public function getById($id){
        $endereco = Endereco::getById($id);
        
        if (!$endereco){
            return response()->json($endereco, 200);
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

        $endereco = Util::varcharToBoolean(Util::toArray($endereco), true, $customVars);

        return response()->json($endereco, 200);
    }

    public function index($id, Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }
        $endereco = Endereco::index($id, $top);

        return response()->json($endereco, 200);
    }

    public function store($id, Request $request){
        $endereco = $this->serializeRequest($request);
        $response = Endereco::store($id, $endereco);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'endereco' => ['Endereço já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Endereco::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Endereco::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'endereco' => ['Não foi possível excluir o Endereço!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    } 

    public function update($id, Request $request){
        $endereco = $this->serializeRequest($request);

        $response = Endereco::updateItem($id, $endereco);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'endereco' => ['Não foi possível alterar o Endereço!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $endereco = Util::booleanToVarchar($requestArray);

        if($endereco->situacao === 'S'){
            $endereco->situacao = 'A';
        } else {
            $endereco->situacao = 'X';
        }

        return $endereco;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'cep'          => ['required'],
            'endereco'     => ['required'],
            'bairro'       => ['required'],
            'numero'       => ['required'],
            'idCidade'     => ['required'],
            'descricaoEnd' => ['required'],
            'situacao'     => ['required']
        ]);

        return true;
    }

    public function endPadrao($idCadastro, Request $request){
        $httpCode = 200;

        $response = EnderecoPadrao::deleteItem($idCadastro);
        $response = EnderecoPadrao::store($idCadastro, $request);

        return response()->json($response, $httpCode);
    }
}

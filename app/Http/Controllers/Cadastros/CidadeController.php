<?php

namespace App\Http\Controllers\Cadastros;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cadastros\Cidade;
use App\Models\Cadastros\Estado;
use App\Models\Cadastros\CidadeRegiao;
use App\Models\Cadastros\PorteCidade;
use App\Util\Util;

class CidadeController extends Controller
{
    public function getPageInfo(Request $request){
        $estados = Estado::index();
        $regioes = CidadeRegiao::index();
        $portes  = PorteCidade::index();

        $response = [
            'estados' => Util::toSelectKeys($estados, 'descricao', 'id_estado'),
            'regioes' => Util::toSelectKeys($regioes, 'descricao', 'id_regiao'),
            'portes'  => Util::toSelectKeys($portes , 'porte', 'id_porte'),
        ];

        return response()->json($response, 200);        
    }

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $cidade = Cidade::index($top);

        return response()->json($cidade, 200);
    }

    public function getById($id){
        $cidade = Cidade::getById($id);
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $cidade = Util::varcharToBoolean(Util::toArray($cidade), true, $customVars);

        return response()->json($cidade, 200);
    }

    public function getCidadeByIbge($ibge){
        $cidade = Cidade::getCidadeByIbge($ibge);

        return response()->json($cidade, 200);
    }

    public function store(Request $request){
        $request->validate([
            'cidade'    => ['required'],
            'idEstado'  => ['required'],
            'idIbge'    => ['required'],
            'situacao'  => ['required'],
            'idRegiao'  => ['required'],
            'idPorte'   => ['required'],
            'capital'   => ['required'],
            'populacao' => ['required']
        ]);

        $cidade = $this->serializeRequest($request);

        $response = Cidade::store($cidade);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'cidade' => ['Cidade já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Cidade::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Cidade::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'cidade' => ['Não foi possível excluir a Cidade!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }   

    public function update($id, Request $request){
        $cidade = $this->serializeRequest($request);        
        $response = Cidade::updateItem($id, $cidade);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'cidade' => ['Não foi possível alterar a Cidade!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $requestArray = json_decode($request->getContent(), true);

        $cidade = Util::booleanToVarchar($requestArray);

        if($cidade->situacao === 'S'){
            $cidade->situacao = 'A';
        } else {
            $cidade->situacao = 'X';
        }

        return $cidade;
    }
}

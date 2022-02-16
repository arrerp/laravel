<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\Deposito;
use App\Models\Cadastros\Empresa;
use App\Models\Cadastros\Parceiro;
use App\Util\Util;

class DepositoController extends Controller
{
    public function getPageInfo(Request $request){
        $empresas  = Empresa::index();
        $parceiros = Parceiro::index();
        $columns   = Deposito::getTableInfo();

        $response = [
            'empresas'  => Util::toSelectKeys($empresas , 'fantasia', 'id_empresa' ),
            'parceiros' => Util::toSelectKeys($parceiros, 'fantasia', 'id_cadastro'),
            'columns'   => $columns,
        ];

        return response()->json($response, 200);        
    }

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $deposito = Deposito::index($top);

        return response()->json($deposito, 200);
    }

    public function getById($id){
        $deposito = Deposito::getById($id);
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $deposito = Util::varcharToBoolean(Util::toArray($deposito), true, $customVars);

        return response()->json($deposito, 200);
    }

    public function getByIdEmp($id){
        $deposito = Deposito::getByIdEmp($id);
        
        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $deposito = Util::varcharToBoolean(Util::toArray($deposito), true, $customVars);

        return response()->json($deposito, 200);
    }    

    public function store(Request $request){
        $deposito = $this->serializeRequest($request);

        $response = Deposito::store($deposito);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'deposito' => ['Depósito já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = Deposito::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Deposito::updateSituacao($id);
                $httpCode = 200;
            }

        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'deposito' => ['Não foi possível excluir o Depósito!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }   

    public function update($id, Request $request){
        $deposito = $this->serializeRequest($request);

        $response = Deposito::updateItem($id, $deposito);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'deposito' => ['Não foi possível alterar o Depósito!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $deposito = Util::booleanToVarchar($requestArray);

        if($deposito->situacao === 'S'){
            $deposito->situacao = 'A';
            
        } else {
            $deposito->situacao = 'X';
        }

        return $deposito;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'idEmpresa'       => ['required'],
            'descricao'       => ['required'],
            'depProprio'      => ['required'],
            'integraSaldo'    => ['required'],
            'integraCusto'    => ['required'], 
            'integraSaldoErp' => ['required'] 
        ]);

        return true;
    }
}

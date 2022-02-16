<?php

namespace App\Http\Controllers\Logistica;

use App\Models\Logistica\ArquivoFrete;
use App\Models\Logistica\TabelaFrete;
use App\Models\Logistica\TipoTransporte;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class TabelaFreteController extends Controller
{

    public function index($id_arquivo){
        $tabelaFrete = TabelaFrete::index($id_arquivo);

        return response()->json($tabelaFrete, 200);
    }

    public function getPageInfo(Request $request){
        $tiposTransporte = TipoTransporte::index();
        $columns         = TabelaFrete::getTableInfo();

        $response = [
            'tiposTransporte' => Util::toSelectKeys($tiposTransporte , 'descFull', 'id_tipoTransp' ),
            'columns'         => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $tabelaFrete = TabelaFrete::getById($id);

        if(!$tabelaFrete){
            return response()->json($tabelaFrete, 200);
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

        $tabelaFrete = Util::varcharToBoolean(Util::toArray($tabelaFrete), true, $customVars);

        $response = [
            'tabelaFrete' => $tabelaFrete
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $this->requestValidate($request);

        $idArquivo = ArquivoFrete::store($request);

        $rows = $request->rows;
        foreach($rows as $tabelaFrete){
            $tabelaFrete = Util::toObject($tabelaFrete);
            $response    = TabelaFrete::store($idArquivo, $tabelaFrete);
        };

        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'tabelaFrete' => ['Tabela de Frete já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $response = TabelaFrete::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'tabelaFrete' => ['Registro referenciado ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'tabelaFrete' => ['Não foi possível excluir a Tabela de Frete!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id, Request $request){
        $tabelaFrete = $this->serializeRequest($request);

        $response = TabelaFrete::updateItem($id, $tabelaFrete);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'tabelaFrete' => ['Não foi possível alterar a Tabela de Frete!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $requestArray = json_decode($request->getContent(), true);

        $transportador = Util::booleanToVarchar($requestArray);

        if($transportador->situacao === 'S'){
            $transportador->situacao = 'A';
            
        } else {
            $transportador->situacao = 'X';
        }


        return $transportador;
    }   
}
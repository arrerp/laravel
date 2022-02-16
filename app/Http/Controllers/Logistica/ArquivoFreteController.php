<?php

namespace App\Http\Controllers\Logistica;

use App\Models\Logistica\ArquivoFrete;
use App\Models\Logistica\TabelaFrete;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\Util;

class ArquivoFreteController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $arquivoFrete = ArquivoFrete::index($top);

        return response()->json($arquivoFrete, 200);
    }

    public function getPageInfo(Request $request){
        $columns = ArquivoFrete::getTableInfo();

        $response = [
            'columns'         => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($id){
        $arquivoFrete = ArquivoFrete::getById($id);

        if(!$arquivoFrete){
            return response()->json($arquivoFrete, 200);
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

        $arquivoFrete = Util::varcharToBoolean(Util::toArray($arquivoFrete), true, $customVars);

        $response = [
            'arquivoFrete' => $arquivoFrete
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $this->requestValidate($request);

        $rows = $request->rows;
        foreach($rows as $tabelaFrete){
            $arquivoFrete = Util::toObject($arquivoFrete);
            $response = ArquivoFrete::store($arquivoFrete);
        };

        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'arquivoFrete' => ['Arquivo já importado. Por favor, troque o nome do Arquivo.']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id){
        $responseTab = TabelaFrete::deleteFile($id);
        
        if(isset($responseTab->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'arquivoFrete' => ['Erro ao excluir as linhas do arquivo!']
                    ]
                ];
            }
        }else{
            $response = ArquivoFrete::deleteItem($id);
            $httpCode = 200;

            if(isset($response->errorInfo)){
                $httpCode = 400;
                if ($response->errorInfo[0]==23000){
                    $response = [
                        'errors' => [
                            'arquivoFrete' => ['Registro referenciado ao sistema!']
                        ]
                    ];
                }
            }else if(!$response){
                $httpCode = 400;
                $response = [
                    'errors' => [
                        'arquivoFrete' => ['Não foi possível excluir a Tabela de Frete!']
                    ]
                ];
            }

            return response()->json($response, $httpCode);

        }    
    }
    public function update($id, Request $request){
        $arquivoFrete = $this->serializeRequest($request);

        $response = ArquivoFrete::updateItem($id, $arquivoFrete);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'arquivoFrete' => ['Não foi possível alterar a Tabela de Frete!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function serializeRequest($request){
        $this->requestValidate($request);

        $requestArray = json_decode($request->getContent(), true);

        $arquivoFrete = Util::booleanToVarchar($requestArray);

        if($arquivoFrete->situacao === 'S'){
            $arquivoFrete->situacao = 'A';
            
        } else {
            $arquivoFrete->situacao = 'X';
        }


        return $arquivoFrete;
    }   
    
    private function requestValidate($request){
        $request->validate([
            'nomeArquivo'  => ['required'],
            'situacao'     => ['required'],
            'idTipoTransp' => ['required'],
        ]);

        return true;
    }
}
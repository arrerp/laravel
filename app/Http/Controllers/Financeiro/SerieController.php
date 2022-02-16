<?php

namespace App\Http\Controllers\Financeiro;

use App\Models\Cadastros\Empresa;
use App\Models\Financeiro\Serie;
use App\Models\Financeiro\TipoSerie;
use App\Http\Controllers\Controller;
use App\Models\Financeiro\ModeloSerie;
use Illuminate\Http\Request;
use App\Util\Util;

class SerieController extends Controller
{

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $serie = Serie::index($top);

        return response()->json($serie, 200);
    }

    public function getPageInfo(Request $request){
        $empresas        = Empresa::index();
        $tiposSerie      = TipoSerie::index();
        $modeloSerie     = ModeloSerie::index();
        $columns         = Serie::getTableInfo();

        $response = [
            'empresas'    => Util::toSelectKeys($empresas, 'fantasia', 'id_empresa' ),
            'tiposSerie'  => Util::toSelectKeys($tiposSerie , 'descricao', 'id_tpSerie' ),
            'modeloSerie' => Util::toSelectKeys($modeloSerie , 'descricao', 'id_modelo' ),
            'columns'     => $columns
        ];

        return response()->json($response, 200);        
    }  

    public function getById($emp, $serie){
        $serieId = Serie::getById($emp, $serie);

        if(!$serie){
            return response()->json($serieId, 200);
        }
        
        $response = [
            'serie' => $serieId
        ];        
        return response()->json($response, 200);
    }

    public function store(Request $request){
        $response = Serie::store($request);
        $httpCode = 200;
        
        if(!$response){
            $response = [
                'errors' => [
                    'serie' => ['Série já cadastrada!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function delete($id_empresa, $v_serie){
        $response = Serie::deleteItem($id_empresa, $v_serie);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = [
                    'errors' => [
                        'serie' => ['Série referenciada ao sistema!']
                    ]
                ];
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'serie' => ['Não foi possível excluir a Série!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    public function update($id_empresa, $v_serie, Request $request){
        $response =Serie::updateItem($id_empresa, $v_serie, $request);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'serie' => ['Não foi possível alterar a Série!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }

    private function requestValidate($request){
        $request->validate([
            'idEmpresa' => ['required'],
            'vSerie'    => ['required'],
            'idTpSerie' => ['required'],
            'idModelo'  => ['required'],
            'descricao' => ['required'],
            'docSeq'    => ['required']
        ]);

        return true;
    }
}
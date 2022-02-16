<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class OperacaoFinanceira extends Model
{
    protected $table = 'OperacaoFinanceira';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'especie',
        'id_aplicacao',
        'id_operacaoEst',
        'sinal',
        'id_funcaoFinanc'
    ];

    static function index($top = 10000){
        $operacaoes = OperacaoFinanceira::join('OrigemOperacao' , 'OperacaoFinanceira.id_aplicacao' , '=', 'OrigemOperacao.id_aplicacao')
                             ->join('FuncaoFinanceira', 'OperacaoFinanceira.id_funcaoFinanc', '=', 'FuncaoFinanceira.id_funcaoFinanc')
                             ->take($top)
                             ->lock('WITH(NOLOCK)')
                             ->selectRaw("OperacaoFinanceira.id_operacao , 
                                          OperacaoFinanceira.descricao   , 
                                          OperacaoFinanceira.especie     , 
                                          OrigemOperacao.id_aplicacao    ,
                                          OrigemOperacao.descricao       ,
                                          OperacaoFinanceira.sinal       , 
                                          FuncaoFinanceira.descricao")
                             ->get();
        return $operacaoes;
    }

    static function getById($id_operacao){
        $operacao = OperacaoFinanceira::where('id_operacao', $id_operacao)
                         ->lock('WITH(NOLOCK)')
                         ->first();

        return $operacao;
    }

    static function store($operacao)
    {
        $response = false;

        $response = OperacaoFinanceira::create([
            'descricao'       => $operacao->descricao,
            'especie'         => $operacao->especie,
            'id_aplicacao'    => $operacao->idAplicacao,
            'id_operacaoEst'  => $operacao->idOperacaoEst,
            'sinal'           => $operacao->sinal,
            'id_funcaoFinanc' => $operacao->idFuncaoFinanc,
        ]);

        return $response;
    }

    static function updateItem($id_operacao, $operacao){
        $update = false;
            
        $update = OperacaoFinanceira::where('id_operacao', $id_operacao)
                                    ->update(['descricao'       => $operacao->descricao,
                                              'especie'         => $operacao->especie,
                                              'id_aplicacao'    => $operacao->idAplicacao,
                                              'id_operacaoEst'  => $operacao->idOperacaoEst,
                                              'sinal'           => $operacao->sinal,
                                              'id_funcaoFinanc' => $operacao->idFuncaoFinanc
        ]);

        return $update;
    }

    static function deleteItem($id_operacao){
        try {
            $delete = OperacaoFinanceira::where('id_operacao', $id_operacao)
                              ->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }   
    
    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }        
}

<?php

namespace App\Models\Materiais;

use Exception;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;


class MovimentoEstoque extends Model
{
    public $timestamps = false;

    protected $table = 'MovimentoEstoque';

    protected $fillable = [
        'id_empresa',
        'id_tipoMovimento',
        'id_deposito',
        'id_depDestino',
        'nr_doc_ref',
        'dt_movimento',
        'id_usuario',
        'observacoes',
    ];

    static function index($top = 1000){
        $movimentos = MovimentoEstoque::take($top)
                                      ->join('TipoMovimento', 'MovimentoEstoque.id_tipoMovimento', '=', 'TipoMovimento.id_tipoMovimento')
                                      ->join('Deposito', 'Deposito.id_deposito', '=', 'MovimentoEstoque.id_deposito')
                                      ->leftJoin('Deposito AS DepositoDestino', 'MovimentoEstoque.id_depDestino', '=', 'DepositoDestino.id_deposito')
                                      ->join('Empresa', 'MovimentoEstoque.id_empresa', '=', 'Empresa.id_empresa')
                                      ->join('Cadastro', 'Empresa.id_empresa', '=', 'Cadastro.id_cadastro')
                                      ->join('users', 'users.id', '=', 'MovimentoEstoque.id_usuario')
                                      ->lock('WITH(NOLOCK)')
                                      ->select([
                                            'MovimentoEstoque.id_movimento AS id_movimento',
                                            'MovimentoEstoque.dt_movimento AS dt_movimento',
                                            'Cadastro.razao_social         AS empresa',
                                            'Cadastro.id_cadastro          AS id_empresa',
                                            'TipoMovimento.descricao       AS tipo_movimento',
                                            'MovimentoEstoque.nr_doc_ref   AS nr_doc_ref',
                                            'Deposito.descricao            AS deposito_origem',
                                            'DepositoDestino.descricao     AS deposito_destino',
                                            'users.name                    AS usuario',
                                            'MovimentoEstoque.observacoes  AS observacoes',
                                            'TipoMovimento.sinal           AS sinal',
                                      ])
                                      ->orderByDesc('dt_movimento')
                                      ->get();                              
        return $movimentos;
    }

    static function store($request)
    {
        $response = false;

        $response = MovimentoEstoque::insertGetId([
            'id_empresa' => $request->idEmpresa,
            'id_tipoMovimento' => $request->idMovimento,
            'id_deposito' => $request->idDepositoOrigem,
            'id_depDestino' => $request->idDepositoDestino,
            'nr_doc_ref' => $request->nrDocRef,
            'dt_movimento' => Now('America/Fortaleza'),
            'id_usuario' => auth()->user()->id,
            'observacoes' => $request->observacoes,
        ]);

        return $response;
    }

    static function destroy($id){
        try {
            $delete = MovimentoEstoque::where('id_movimento', $id)
                                      ->delete();

        } catch(Exception $e){
            $delete = false;
        }
        
        return $delete;
    }

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }    
}


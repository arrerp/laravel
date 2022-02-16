<?php

namespace App\Models\Materiais;

use Exception;
use Illuminate\Database\Eloquent\Model;

class TransformaProduto extends Model
{
    public $timestamps = false;

    protected $table = 'TransformaProduto';

    protected $fillable = [
        'id_transformacao',
        'id_empresa',
        'id_depositoOri',
        'id_depositoDes',
        'dt_transformacao',
        'id_usuario',
        'observacao',
        'situacao'
    ];

    static function index($top = 1000){
        $transformacoes = TransformaProduto::take($top)
                                      ->join('Deposito', 'Deposito.id_deposito', '=', 'TransformaProduto.id_deposito')
                                      ->leftJoin('Deposito AS DepositoDestino', 'TransformaProduto.id_depDestino', '=', 'DepositoDestino.id_deposito')
                                      ->join('Empresa', 'TransformaProduto.id_empresa', '=', 'Empresa.id_empresa')
                                      ->join('Cadastro', 'Empresa.id_empresa', '=', 'Cadastro.id_cadastro')
                                      ->join('users', 'users.id', '=', 'TransformaProduto.id_usuario')
                                      ->lock('WITH(NOLOCK)')
                                      ->select([
                                            'TransformaProduto.id_transformacao AS id_transformacao',
                                            'TransformaProduto.dt_transformacao AS dt_transformacao',
                                            'Cadastro.razao_social         AS empresa',
                                            'Deposito.descricao            AS deposito_origem',
                                            'DepositoDestino.descricao     AS deposito_destino',
                                            'users.name                    AS usuario',
                                            'TransformaProduto.observacao  AS observacoes',
                                            'TransformaProduto.situacao    AS situacao'
                                      ])
                                      ->get();                              
        return $transformacoes;
    }

    static function store($request)
    {
        $response = false;

        $response = TransformaProduto::insertGetId([
            'id_empresa' => $request->idEmpresa,
            'id_depositoOri' => $request->idDepositoOrigem,
            'id_depositoDes' => $request->idDepositoDestino,
            'dt_transformacao' => Now('America/Fortaleza'),
            'id_usuario' => auth()->user()->id,
            'observacao' => $request->observacoes,
            'situacao' => 'A'
        ]);

        return $response;
    }

    static function destroy($id){
        try {
            $delete = TransformaProduto::where('id_transformacao', $id)
                                      ->delete();

        } catch(Exception $e){
            $delete = false;
        }
        
        return $delete;
    }
}


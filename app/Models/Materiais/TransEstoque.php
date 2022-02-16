<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransEstoque extends Model
{
    protected $table = 'TransEstoque';

    public $timestamps = false;

    protected $fillable = [
        'id_transEstoque',
        'id_empresa',
        'id_deposito',
        'id_produto',
        'dt_movimento',
        'qt_entrada',
        'qt_saida',
        'vl_unitario',
        'id_estorno',
        'nr_lote',
        'dt_validade',
        'dt_fabricacao'
    ];

    static function saldoFisico($id_empresa, $id_deposito, $id_produto){
        $transEstoque = TransEstoque::where('id_empresa' , $id_empresa)
                                    ->where('id_deposito', $id_deposito)                                    
                                    ->where('id_produto' , $id_produto)                                          
                                    ->lock('WITH(NOLOCK)')
                                    ->selectRaw("ISNULL(SUM(ISNULL(qt_entrada, 0) - ISNULL(qt_saida, 0)), 0) qt_fisico")
                                    ->first();
        return $transEstoque;
    }       
}

<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoUnidade extends Model
{
    public $timestamps = false;

    protected $table = 'ProdutoUnidade';

    protected $fillable = [
        'descricao'
    ];

    static function index($top = 1000){
        $unidades = ProdutoUnidade::take($top)
                              ->lock('WITH(NOLOCK)')
                              ->get()
                              ->toArray();

        return $unidades;
    }

    static function getWhere($prazo){
        $unidades = ProdutoUnidade::where('descricao', $prazo)->lock('WITH(NOLOCK)')->get();

        return $unidades;
    }
}

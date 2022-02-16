<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class CondicaoProduto extends Model
{
    public $timestamps = false;

    protected $table = 'CondicaoProduto';

    protected $fillable = [
        'descricao'
    ];

    static function index($top = 1000){
        $condicao = CondicaoProduto::take($top)
                              ->lock('WITH(NOLOCK)')
                              ->get()
                              ->toArray();

        return $condicao;
    }

    static function getWhere($prazo){
        $condicao = CondicaoProduto::where('descricao', $prazo)->lock('WITH(NOLOCK)')->get();

        return $condicao;
    }
}

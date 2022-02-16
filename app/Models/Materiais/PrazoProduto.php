<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class PrazoProduto extends Model
{
    public $timestamps = false;

    protected $table = 'PrazoProduto';

    protected $fillable = [
        'descricao'
    ];

    static function index($top = 1000){
        $prazos = PrazoProduto::take($top)
                              ->lock('WITH(NOLOCK)')
                              ->get()
                              ->toArray();

        return $prazos;
    }

    static function getWhere($prazo){
        $prazos = PrazoProduto::where('descricao', $prazo)->lock('WITH(NOLOCK)')->get();

        return $prazos;
    }
}

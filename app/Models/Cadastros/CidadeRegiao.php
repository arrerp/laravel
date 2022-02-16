<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CidadeRegiao extends Model
{
    protected $table = 'CidadeRegiao';

    public $timestamps = false;

    protected $fillable = [
        'id_regiao',
        'descricao'
    ];

    static function index($top = 1000){
        $regiao = CidadeRegiao::take($top)
                        ->lock('WITH(NOLOCK)')
                        ->get()
                        ->toArray();

        return $regiao;                              
    }

    static function getWhere($regiao){
        $regioes = CidadeRegiao::where('descricao', $regiao)->lock('WITH(NOLOCK)')->get();

        return $regioes;
    }

    static function getById($id){
        $regioes = CidadeRegiao::where('id_regiao', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $regioes;
    }

    static function store($regiao)
    {
        $response = false;

        $response = CidadeRegiao::insertGetId([
            'descricao'        => $regiao->descricao
        ]);

        return $response;
    }

    static function existsItem($regiao)
    {
        return CidadeRegiao::where('descricao', $regiao)->exists();
    }

    static function updateItem($id_regiao, $descricao){
        $update = false;

        if(!CidadeRegiao::existsItem($descricao)){
            $update = CidadeRegiao::where('id_regiao', $id_regiao)->update([
                'descricao'        => $descricao
            ]);
        }

        return $update;
    }

    static function deleteItem($id_regiao){
        
        $delete = CidadeRegiao::where('id_regiao', $id_regiao)->delete();

        return $delete;
    }
}

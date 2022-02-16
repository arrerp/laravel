<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'Estado';

    public $timestamps = false;

    protected $fillable = [
        'id_estado',
        'sigla',
        'cd_uf_favorecida',
        'cd_ibge',
        'descricao'
    ];

    static function index($top = 1000){
        $estado = Estado::take($top)
                        ->lock('WITH(NOLOCK)')
                        ->get()
                        ->toArray();

        return $estado;                              
    }

    static function getWhere($estado){
        $estados = Estado::where('estado', $estado)->lock('WITH(NOLOCK)')->get();

        return $estados;
    }

    static function getById($id){
        $estados = Estado::where('id_estado', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $estados;
    }

    static function store($estado)
    {
        $response = false;

        $response = Estado::insertGetId([
            'id_pais'          => $estado->id_pais, 
            'sigla'            => $estado->sigla  ,
            'cd_uf_favorecida' => $estado->cd_uf_favorecida  ,
            'cd_ibge'          => $estado->cd_ibge,
            'descricao'        => $estado->descricao
        ]);

        return $response;
    }

    static function existsItem($estado)
    {
        return Estado::where('estado', $estado)->exists();
    }

    static function updateItem($id_estado, $estado){
        $update = false;

        if(!Estado::existsItem($estado->estado)){
            $update = Estado::where('id_estado', $id_estado)->update([
                'id_pais'          => $estado->id_pais, 
                'sigla'            => $estado->sigla  ,
                'cd_uf_favorecida' => $estado->cd_uf_favorecida  ,
                'cd_ibge'          => $estado->cd_ibge,
                'descricao'        => $estado->descricao
            ]);
        }

        return $update;
    }

    static function deleteItem($id_estado){
        
        $delete = Estado::where('id_estado', $id_estado)->delete();

        return $delete;
    }
}

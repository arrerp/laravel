<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposEmail extends Model
{
    protected $table = 'TipoEmail';

    public $timestamps = false;

    protected $fillable = [
        'descricao'
    ];

    static function index($top = 1000){
        $tiposEmail = TiposEmail::take($top)->lock('WITH(NOLOCK)')->get();

        return $tiposEmail;
    }

    static function getWhere($tipoEmail){
        $tiposEmail = TiposEmail::where('descricao', $tipoEmail)->lock('WITH(NOLOCK)')->get();

        return $tiposEmail;
    }

    static function store($tipoEmail)
    {
        $response = false;

        if(!TiposEmail::existsItem($tipoEmail)){
            $response = TiposEmail::insertGetId([
                'descricao' => $tipoEmail
            ]);
        }

        return $response;
    }

    static function existsItem($tipoEmail)
    {
        return TiposEmail::where('descricao', $tipoEmail)->exists();
    }


    static function updateItem($id_tpEmail, $tipoEmail){
        $update = false;

        if(!TiposEmail::existsItem($id_tpEmail)){
            $update = TiposEmail::where('id_tpEmail', $id_tpEmail)
                                ->update([
                                    'descricao' => $tipoEmail
                                ]);
        }

        return $update;
    }

    static function deleteItem($id_tpEmail){
        $delete = TiposEmail::where('id_tpEmail', $id_tpEmail)->delete();

        return $delete;
    }
}

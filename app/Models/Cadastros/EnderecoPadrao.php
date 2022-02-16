<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnderecoPadrao extends Model
{
    protected $table = 'EnderecoPadrao'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'id_endereco'
    ];

    static function store($id_cadastro, $endereco)
    {
        $response = false;

        $response = EnderecoPadrao::insertGetId([
            'id_cadastro' => $id_cadastro,
            'id_endereco' => $endereco->idEndereco
        ]);

        return $response;
    }

    static function deleteItem($id_cadastro){
        
        $delete = EnderecoPadrao::where('id_cadastro', $id_cadastro)->delete();

        return $delete;
    }
}

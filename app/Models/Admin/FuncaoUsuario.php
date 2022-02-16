<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncaoUsuario extends Model
{
    protected $table = 'FuncaoUsuario';

    public $timestamps = false;

    protected $fillable = [
        'id_funcao',
        'funcao'
    ];
   

    static function index($top = 1000){
        $funcaoUsuario = FuncaoUsuario::take($top)
                        ->lock('WITH(NOLOCK)')
                        ->get()
                        ->toArray();

        return $funcaoUsuario;
    }

    static function store($form)
    {
        $response = false;

        $response = FuncaoUsuario::insertGetId([
            'funcao'    => $form->funcao
        ]);

        return $response;
    }

    static function deleteItem($id_funcao){
        try {
            $delete = FuncaoUsuario::where('id_funcao', $id_funcao)->delete();
            } catch(\Illuminate\Database\QueryException $ex){
                $delete = $ex;
            }
        return $delete;
    }

    static function existsItem($funcaoUsuario)
    {
        return FuncaoUsuario::where('funcao', $funcaoUsuario)->exists();
    }

    static function updateItem($id_funcao, $funcaoUsuario){
        $update = false;

        if(!FuncaoUsuario::existsItem($funcaoUsuario->funcao)){
            $update = FuncaoUsuario::where('id_funcao', $id_funcao)->update([
                'funcao'    => $funcaoUsuario->funcao
            ]);
        }

        return $update;
    }

}

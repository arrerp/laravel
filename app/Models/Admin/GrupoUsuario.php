<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoUsuario extends Model
{
    protected $table = 'GrupoUsuario';

    public $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'grupo'
    ];

    static function index($top = 1000){
        $grupoUsuario = GrupoUsuario::take($top)
                        ->lock('WITH(NOLOCK)')
                        ->get()
                        ->toArray();

        return $grupoUsuario;                              
    }

    static function getWhere($grupoUsuario){
        $grupoUsuario = GrupoUsuario::where('grupo', $grupoUsuario)->lock('WITH(NOLOCK)')->get();

        return $grupoUsuario;
    }

    static function getById($id){
        $grupoUsuario = GrupoUsuario::where('id_grupo', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $grupoUsuario;
    }

    static function store($grupoUsuario)
    {
        $response = false;

        $response = GrupoUsuario::insertGetId([
            'grupo'    => $grupoUsuario->grupo
        ]);

        return $response;
    }

    static function existsItem($grupoUsuario)
    {
        return GrupoUsuario::where('grupo', $grupoUsuario)->exists();
    }

    static function updateItem($id_grupo, $grupoUsuario){
        $update = false;

        if(!GrupoUsuario::existsItem($grupoUsuario->grupo)){
            $update = GrupoUsuario::where('id_grupo', $id_grupo)->update([
                'grupo'    => $grupoUsuario->grupo
            ]);
        }

        return $update;
    }

    static function deleteItem($id_grupo){
        try {
            $delete = GrupoUsuario::where('id_grupo', $id_grupo)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    }
}

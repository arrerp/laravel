<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Util\Util;


class DepositoUsuarios extends Model
{
    protected $table = 'DepositoUsuario';

    public $timestamps = false;

    protected $fillable = [
        'id_deposito',
        'id_usuario',
        'mov_entrada',
        'mov_saida'
    ];

    static function index($idDep, $top = 1000){
        $depositoUsuarios = DepositoUsuarios::join('users' , 'DepositoUsuario.id_usuario' , '=', 'users.id')
                                            ->where('id_deposito', $idDep)
                                            ->take($top)
                                            ->lock('WITH(NOLOCK)')
                                            ->select(["users.id", 
                                                      "users.name", 
                                                      "mov_entrada", 
                                                      "mov_saida"
                                            ])
                                            ->orderBy('users.name')->get();
        return $depositoUsuarios;
    }

    static function getById($idDep, $idUsu){
        $depositoUsuarios = DepositoUsuarios::where('id_deposito', $idDep)
                                            ->where('id_usuario', $idUsu)
                                            ->lock('WITH(NOLOCK)')
                                            ->first();

        return $depositoUsuarios;
    }

    static function store($idDep, $depositoUsuarios)
    {
        $response = false;

        $response = DepositoUsuarios::create([
            'id_deposito' => $idDep, 
            'id_usuario'  => $depositoUsuarios->idUsuario ,
            'mov_entrada' => $depositoUsuarios->movEntrada,
            'mov_saida'   => $depositoUsuarios->movSaida 
        ]);

        return $response;
    }

    static function updateItem($idDep, $idUsu, $depositoUsuarios){
        $update = false;
            
        $update = DepositoUsuarios::where('id_deposito', $idDep)
                                  ->where('id_usuario', $idUsu)
                                  ->update([
                                    'mov_entrada' => $depositoUsuarios->movEntrada,
                                    'mov_saida'   => $depositoUsuarios->movSaida 
                                  ]);

        return $update;
    }

    static function deleteItem($idDep, $idUsu){
        
        $delete = DepositoUsuarios::where('id_deposito', $idDep)
                                  ->where('id_usuario' , $idUsu)
                                  ->delete();

        return $delete;
    }

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }          

    //--:: Busca os Usuários Válidos para inserir no Depósito
    static function getUsers($idDep, $top = 10000){
        $depositoUsuario = User::take($top)
                               ->lock('WITH(NOLOCK)')
                               ->whereRaw("Users.id NOT IN(SELECT DepositoUsuario.id_usuario
                                                             FROM DepositoUsuario WITH(NOLOCK)
                                                            WHERE DepositoUsuario.id_deposito = $idDep)")
                               ->selectRaw("Users.id,
                                            RTRIM(users.id) + ' - ' + RTRIM(users.name) as idName, 
                                            users.name") 
                               ->orderBy('name')                                
                               ->get();
        return $depositoUsuario;
    }     
   
}

<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Util\Util;

class Usuario extends Model
{
    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'id_grupoUsuario',
        'id_funcao',
        'id_empresa',
        'permite_env_email',
        'permite_env_whats',
        'ramal',
    ];

    static function index($top = 100){
        $cadastros = Usuario::leftjoin('FuncaoUsuario', 'users.id_funcao', '=', 'FuncaoUsuario.id_funcao')
                             ->leftJoin('Cadastro', 'users.id_empresa'  , '=', 'Cadastro.id_cadastro')
                             ->leftJoin('GrupoUsuario', 'users.id_grupoUsuario'  , '=', 'GrupoUsuario.id_grupo')
                             ->take($top)
                             ->lock('WITH(NOLOCK)')
                             ->selectRaw("users.id,
                             users.name, 
                             users.email,
                             users.created_at,
                             users.updated_at,
                             GrupoUsuario.grupo,
                             CASE WHEN users.situacao = 'A' THEN 'Ativo'
                                  WHEN users.situacao = 'X' THEN 'Inativo' END situacao,
                             FuncaoUsuario.funcao,
                             Cadastro.fantasia,
                             users.permite_env_email,
                             users.permite_env_whats,
                             ISNULL(users.tel_whats, '') tel_whats,
                             users.ramal,
                             users.url_img")
                            ->orderBy('name', 'asc')
                            ->get();
        return $cadastros;
    }

    static function getWhere($usuarios){
        $usuarios = Usuario::where('email', $usuario)->lock('WITH(NOLOCK)')->get();

        return $usuarios;
    }

    static function getById($id){
        $usuarios = Usuario::where('id', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $usuarios;
    }

    static function store($usuario)
    {
        $response = false;

        if(!Usuario::existsItem($usuario->email)){
            $response = Usuario::insertGetId([
                'name'              => $usuario->name  ,
                'email'             => $usuario->email ,
                'password'          => Hash::make($usuario->email),
                'created_at'        => Now('America/Fortaleza'),
                'id_grupoUsuario'   => $usuario->idGrupoUsuario,
                'situacao'          => 'A',
                'id_funcao'         => $usuario->idFuncao,   
                'id_supervisor'     => $usuario->idSupervisor,
                'id_empresa'        => $usuario->idEmpresa,
                'permite_env_email' => $usuario->permiteEnvEmail,
                'permite_env_whats' => $usuario->permiteEnvWhats,
                'tel_whats'         => $usuario->telWhats,
                'ramal'             => $usuario->ramal,
                'url_img'           => '',
            ]);
        }
        return $response;
    }

    static function existsItem($usuario)
    {
        return Usuario::where('email', $usuario)->exists();
    }

    static function updateItem($id, $usuario){
        $update = false;
            
        $update = Usuario::where('id', $id)->update([
            'name'              => $usuario->name  ,
            'email'             => $usuario->email ,
            'updated_at'        => Now('America/Fortaleza'),
            'id_grupoUsuario'   => $usuario->idGrupoUsuario,
            'id_funcao'         => $usuario->idFuncao,   
            'id_supervisor'     => $usuario->idSupervisor,
            'id_empresa'        => $usuario->idEmpresa,
            'permite_env_email' => $usuario->permiteEnvEmail,
            'permite_env_whats' => $usuario->permiteEnvWhats,
            'tel_whats'         => $usuario->telWhats,
            'situacao'          => 'A',
            'ramal'             => $usuario->ramal,
            'url_img'           => ''
        ]);

        return $update;
    }

    static function updatePhoto($id, $caminho){
        $update = false;
            
        $update = Usuario::where('id', $id)->update([
            'url_img'           => $caminho
        ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Usuario::where('id', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }    

    static function deleteItem($id){
        try {
            $delete = Usuario::where('id', $id)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    }    
}

<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroClasseEmail extends Model
{
    protected $table = 'CadastroClasseEmail';

    public $timestamps = false;

    protected $fillable = [
        'id_classeCliente',
        'id_classe',
        'id_cadastro',
        'email'
    ];


    static function index($id, $top = 10000){
        $emailCadastro = CadastroClasseEmail::join('ClasseEmail', 'CadastroClasseEmail.id_classe', '=', 'ClasseEmail.id_classe')
                                            ->join('Cadastro', 'CadastroClasseEmail.id_cadastro', '=', 'Cadastro.id_cadastro')
                                            ->selectRaw("ClasseEmail.*,
                                                         CadastroClasseEmail.*,
                                                         Cadastro.razao_social
                                            ")
                                            ->take($top)
                                            ->lock('WITH(NOLOCK)')
                                            ->where('CadastroClasseEmail.id_cadastro', $id)
                                            ->get();
        return $emailCadastro;
    }

    static function store($req)
    {
        $response = CadastroClasseEmail::insertGetId([
            'id_classe' => $req->classes,
            'id_cadastro' => $req->id_cadastro,
            'email' => $req->email

        ]);
        return $response;
    }

    static function deleteItem($id){
        try {
        $delete = CadastroClasseEmail::where('id_classeCliente', $id)->delete();
        } catch(\Illuminate\Database\QueryException $ex){
            $delete = $ex;
        }
        return $delete;
    }

    static function existsItem($req){
        return CadastroClasseEmail::where('id_classe', $req->classes)->where('email', $req->email)->exists();
    }


}

<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteEmail extends Model
{
    protected $table = 'CadastroEmail';

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'email',
        'id_tpEmail'
    ];


    static function index($id, $top = 10000){
        $clienteEmail = ClienteEmail::join('TipoEmail' , 'CadastroEmail.id_tpEmail', '=', 'TipoEmail.id_tpEmail')
                                    ->take($top)
                                    ->lock('WITH(NOLOCK)')
                                    ->selectRaw("id_cadEmail,
                                                 descricao,
                                                 email")
                                    ->where('id_cadastro', $id)
                                    ->get();
        return $clienteEmail;
    }

    

    static function getById($id){
        $clienteEmail = ClienteEmail::where('id_cadEmail', $id)
                                    ->lock('WITH(NOLOCK)')
                                    ->first();

        return $clienteEmail;
    }

    static function store($id_cadastro, $clienteEmail)
    {
        $response = false;
        $response = ClienteEmail::insertGetId([
            'id_cadastro' => $id_cadastro        ,
            'email'       => $clienteEmail->email,
            'id_tpEmail'  => $clienteEmail->idTpEmail
        ]);

        return $response;
    }

    static function updateItem($id_cadEmail, $clienteEmail){
        $update = false;

        $update = ClienteEmail::where('id_cadEmail', $id_cadEmail)->update([
            'id_cadastro' => $clienteEmail->id_cadastro,
            'email'       => $clienteEmail->email,
            'id_tpEmail'  => $clienteEmail->idTpEmail
        ]);

        return $update;
    }

    static function deleteItem($id_cadEmail){

        $delete = ClienteEmail::where('id_cadEmail', $id_cadEmail)->delete();

        return $delete;
    }

    private function requestValidate($request){
        $request->validate([
            'email'      => ['required'],
            'id_tpEmail' => ['required']
        ]);

        return true;
    }

}

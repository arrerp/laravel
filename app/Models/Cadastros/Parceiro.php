<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;


class Parceiro extends Model
{
    protected $table = 'Parceiro';

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'situacao',
        'observacao'
    ];

    static function index($top = 1000){
        $parceiro = Parceiro::join('Cadastro', 'Parceiro.id_cadastro', '=', 'Cadastro.id_cadastro')
                             ->join('Cidade', 'Cadastro.id_cidade', '=', 'Cidade.id_cidade')
                             ->take($top)
                             ->lock('WITH(NOLOCK)')
                             ->selectRaw("Cadastro.id_cadastro, 
                                          Cadastro.razao_social , 
                                          Cadastro.fantasia     , 
                                          Cadastro.cnpj_cpf     , 
                                          Cadastro.insc_estadual,
                                          Cadastro.cep          , 
                                          Cadastro.endereco     , 
                                          Cadastro.complemento  , 
                                          Cadastro.bairro       , 
                                          Cadastro.numero       , 
                                          Cidade.cidade         , 
                                          Cadastro.fone_resid, 
                                          Cadastro.fone_cel, 
                                          Cadastro.fone_com, 
                                          Cadastro.email        , 
                                          CASE WHEN Parceiro.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END situacao, 
                                          Parceiro.observacao")
                             ->get();
        return $parceiro;
    }

    static function getWhere($parceiro){
        $parceiros = Parceiro::where('id_cadastro', $parceiro)->lock('WITH(NOLOCK)')->get();

        return $parceiros;
    }

    static function getById($id){
        $parceiros = Parceiro::where('id_cadastro', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $parceiros;
    }

    static function store($id_parceiro, $parceiro)
    {
        $response = false;

        if(!Parceiro::existsItem($id_parceiro, $parceiro)){
            $response = Parceiro::create([
                'id_cadastro'  => $id_parceiro,
                'situacao'     => $parceiro->situacao,
                'observacao'   => $parceiro->observacao
            ]);
        }

        return $response;
    }

    static function existsItem($parceiro)
    {
        return Parceiro::where('id_cadastro', $parceiro)->exists();
    }

    static function updateItem($id_parceiro, $parceiro){
        $update = false;

        $update = Parceiro::where('id_cadastro', $id_parceiro)->update([
            'situacao'   => $parceiro->situacao,
            'observacao' => $parceiro->observacao
        ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Parceiro::where('id_cadastro', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }    

    static function deleteItem($id){
        try {
            $delete = Parceiro::where('id_cadastro', $id)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    } 

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }        
}

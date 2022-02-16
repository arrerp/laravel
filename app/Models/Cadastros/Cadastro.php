<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Cadastro extends Model
{
    protected $table = 'Cadastro'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'razao_social',
        'fantasia',
        'cnpj_cpf',
        'rg',
        'insc_estadual',
        'insc_municipal',
        'insc_atividade',
        'cep',
        'endereco',
        'complemento',
        'bairro',
        'numero',
        'id_cidade',
        'fone_resid',
        'fone_cel',
        'fone_com',
        'email',
        'site',
        'referencia',
        'responsavel',
        'dt_registro',
        'situacao',
    ];

    static function index($top = 100){
        $cadastros = Cadastro::join('Cidade', 'Cadastro.id_cidade', '=', 'Cidade.id_cidade')
                             ->join('Estado', 'Cidade.id_estado'  , '=', 'Estado.id_estado')
                             ->take($top)
                             ->lock('WITH(NOLOCK)')
                             ->selectRaw("Cadastro.id_cadastro   , 
                                          Cadastro.razao_social  , 
                                          Cadastro.fantasia      , 
                                          Cadastro.cnpj_cpf      , 
                                          Cadastro.rg            , 
                                          Cadastro.insc_estadual , 
                                          Cadastro.insc_municipal, 
                                          Cadastro.insc_atividade, 
                                          Cadastro.cep           , 
                                          Cadastro.endereco      , 
                                          Cadastro.complemento   , 
                                          Cadastro.bairro        , 
                                          Cadastro.numero        , 
                                          Cadastro.fone_resid    , 
                                          Cadastro.fone_cel      , 
                                          Cadastro.fone_com      , 
                                          Cadastro.email         ,
                                          Cadastro.site          , 
                                          Cadastro.referencia    , 
                                          Cadastro.responsavel   , 
                                          Cadastro.dt_registro   , 
                                          Cadastro.situacao      , 
                                          CASE WHEN Cadastro.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END DescSituacao, 
                                          Cidade.cidade          , 
                                          Estado.descricao, 
                                          Estado.sigla")
                            ->orderBy('razao_social', 'asc')
                            ->get();
        return $cadastros;
    }

    static function getWhere($cadastro){
        $cadastros = Cadastro::where('razao_social', $cadastro)->lock('WITH(NOLOCK)')->get();

        return $cadastros;
    }

    static function getById($id){
        $cadastros = Cadastro::where('id_cadastro', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $cadastros;
    }

    static function store($cadastro)
    {
        $response = false;

        $response = Cadastro::insertGetId([
            'razao_social'   => $cadastro->razaoSocial  ,
            'fantasia'       => $cadastro->fantasia     ,
            'cnpj_cpf'       => Util::onlyNumbers($cadastro->cnpjCpf),
            'rg'             => Util::onlyNumbers($cadastro->rg)           ,
            'insc_estadual'  => $cadastro->inscEstadual ,
            'insc_municipal' => $cadastro->inscMunicipal,
            'insc_atividade' => $cadastro->inscAtividade,
            'cep'            => Util::onlyNumbers($cadastro->cep)          ,
            'endereco'       => $cadastro->endereco     ,
            'complemento'    => $cadastro->complemento  ,
            'bairro'         => $cadastro->bairro       ,
            'numero'         => $cadastro->numero       ,
            'id_cidade'      => $cadastro->idCidade     ,
            'fone_resid'     => Util::onlyNumbers($cadastro->foneResid)    ,
            'fone_cel'       => Util::onlyNumbers($cadastro->foneCel)      ,
            'fone_com'       => Util::onlyNumbers($cadastro->foneCom)      ,
            'email'          => $cadastro->email        ,
            'site'           => $cadastro->site         ,
            'referencia'     => $cadastro->referencia   ,
            'responsavel'    => $cadastro->responsavel  ,
            'dt_registro'    => Now('America/Fortaleza'),
            'situacao'       => 'A'
        ]);

        return $response;
    }

    static function existsItem($cadastro)
    {
        return Cadastro::where('razao_social', $cadastro)->exists();
    }

    static function existsCnpjCpf($cadastro)
    {
        return Cadastro::where('cnpj_cpf', $cadastro)->exists();
    }    

    static function updateItem($id_cadastro, $cadastro){
        $update = false;
            
        $update = Cadastro::where('id_cadastro', $id_cadastro)->update([
            'razao_social'   => $cadastro->razaoSocial  ,
            'fantasia'       => $cadastro->fantasia     ,
            'rg'             => Util::onlyNumbers($cadastro->rg)           ,
            'insc_estadual'  => $cadastro->inscEstadual ,
            'insc_municipal' => $cadastro->inscMunicipal,
            'insc_atividade' => $cadastro->inscAtividade,
            'cep'            => Util::onlyNumbers($cadastro->cep)          ,
            'endereco'       => $cadastro->endereco     ,
            'complemento'    => $cadastro->complemento  ,
            'bairro'         => $cadastro->bairro       ,
            'numero'         => $cadastro->numero       ,
            'id_cidade'      => $cadastro->idCidade     ,
            'fone_resid'     => Util::onlyNumbers($cadastro->foneResid)    ,
            'fone_cel'       => Util::onlyNumbers($cadastro->foneCel)      ,
            'fone_com'       => Util::onlyNumbers($cadastro->foneCom)      ,
            'email'          => $cadastro->email        ,
            'site'           => $cadastro->site         ,
            'referencia'     => $cadastro->referencia   ,
            'responsavel'    => $cadastro->responsavel  ,
            'situacao'       => $cadastro->situacao     ,
            'dt_registro'    => Now('America/Fortaleza')
        ]);

        return $update;
    }


    static function updateSituacao($id){
        $update = false;
            
        $update = Cadastro::where('id_cadastro', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }

    static function deleteItem($id_tabPreco){
        try {
        $delete = Cadastro::where('id_cadastro', $id_tabPreco)->delete();
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

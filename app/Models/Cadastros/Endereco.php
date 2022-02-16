<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Endereco extends Model
{
    protected $table = 'Endereco'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_endereco',
        'id_cadastro',
        'cep',
        'endereco',
        'complemento',
        'bairro',
        'numero',
        'id_cidade',
        'referencia',
        'responsavel',
        'situacao', 
        'descricao_end'
    ];


    static function index($id, $top = 10000){
        $endereco = Endereco::join('Cidade' , 'Endereco.id_cidade', '=', 'Cidade.id_cidade')
                            ->join('Estado' , 'Cidade.id_estado'  , '=', 'Estado.id_estado')
                            ->leftJoin('EnderecoPadrao', 'Endereco.id_endereco', '=', 'EnderecoPadrao.id_endereco' )
                            ->take($top)
                            ->lock('WITH(NOLOCK)')
                            ->selectRaw("Endereco.id_endereco  ,
                                         Endereco.descricao_end, 
                                         Endereco.cep          , 
                                         Endereco.endereco     , 
                                         Endereco.complemento  , 
                                         Endereco.bairro       , 
                                         Endereco.numero       ,
                                         RTRIM(Cidade.cidade) + ' - ' + RTRIM(Estado.sigla) cidade,
                                         Endereco.situacao     , 
                                         CASE WHEN Endereco.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END DescSituacao, 
                                         EnderecoPadrao.id_endereco id_endPadrao")
                            ->where('Endereco.id_cadastro', $id) 
                            ->orderBy('Endereco.id_endereco')
                            ->get();
        return $endereco;
    }    

    static function getById($id){
        $enderecos = Endereco::where('id_endereco', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $enderecos;
    }

    static function store($id_cadastro, $endereco)
    {
        $response = false;

        $response = Endereco::insertGetId([
            'id_cadastro' => $id_cadastro          ,
            'cep'         => $endereco->cep        ,
            'endereco'    => $endereco->endereco   ,
            'complemento' => $endereco->complemento,
            'bairro'      => $endereco->bairro     ,
            'numero'      => $endereco->numero     ,
            'id_cidade'   => $endereco->idCidade   ,
            'referencia'  => $endereco->referencia ,
            'responsavel' => $endereco->responsavel,
            'situacao'    => 'A', 
            'descricao_end' => $endereco->descricaoEnd
        ]);

        return $response;
    }

    static function updateItem($id_endereco, $endereco){
        $update = false;
            
        $update = Endereco::where('id_endereco', $id_endereco)->update([
            'cep'           => $endereco->cep         ,
            'endereco'      => $endereco->endereco    ,
            'complemento'   => $endereco->complemento ,
            'bairro'        => $endereco->bairro      ,
            'numero'        => $endereco->numero      ,
            'id_cidade'     => $endereco->idCidade    ,
            'referencia'    => $endereco->referencia  ,
            'responsavel'   => $endereco->responsavel ,
            'descricao_end' => $endereco->descricaoEnd,
            'situacao'      => $endereco->situacao
        ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Endereco::where('id_endereco', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }


    static function deleteItem($idEndereco){
        try {
            $delete = Endereco::where('id_endereco', $idEndereco)->delete();
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

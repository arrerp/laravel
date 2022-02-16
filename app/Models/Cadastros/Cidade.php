<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    protected $table = 'Cidade';

    public $timestamps = false;

    protected $fillable = [
        'id_cidade',
        'cidade',
        'id_estado',
        'id_ibge',
        'situacao',
        'id_regiao',
        'id_porte',
        'capital', 
        'populacao'
    ];

    static function index($top = 10000){
        $cidades = Cidade::join('Estado'      , 'Cidade.id_estado', '=', 'Estado.id_estado')
                         ->join('PorteCidade' , 'Cidade.id_porte' , '=', 'PorteCidade.id_porte')
                         ->join('CidadeRegiao', 'Cidade.id_regiao', '=', 'CidadeRegiao.id_regiao')
                         ->take($top)
                         ->lock('WITH(NOLOCK)')
                         ->selectRaw("Cidade.id_cidade , 
                                      Cidade.cidade    ,
                                      Estado.sigla     , 
                                      Cidade.id_ibge   , 
                                      CASE WHEN situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END situacao, 
                                      CidadeRegiao.descricao,
                                      PorteCidade.porte,
                                      Cidade.populacao , 
                                      CASE WHEN capital = 'S' THEN 'Sim' ELSE 'Não' END capital,
                                      populacao, 
                                      RTRIM(Cidade.cidade) + ' - ' + RTRIM(Estado.sigla) cidade_uf")
                         ->orderBy('cidade')
                         ->get();
        return $cidades;
    }

    static function getWhere($cidade){
        $cidades = Cidade::where('cidade', $cidade)->lock('WITH(NOLOCK)')->get();

        return $cidades;
    }

    static function getById($id){
        $cidades = Cidade::where('id_cidade', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $cidades;
    }

    static function getCidadeByIbge($ibge){
        $cidade = Cidade::where('id_ibge', $ibge)
                        ->lock('WITH(NOLOCK)')
                        ->first();

        return $cidade;
    }

    static function store($cidade)
    {
        $response = false;

        $response = Cidade::insertGetId([
            'cidade'    => $cidade->cidade  , 
            'id_estado' => $cidade->idEstado,
            'id_ibge'   => $cidade->idIbge  ,
            'id_regiao' => $cidade->idRegiao  ,
            'situacao'  => 'A'              ,
            'id_porte'  => $cidade->idPorte ,
            'capital'   => $cidade->capital ,
            'populacao' => $cidade->populacao
        ]);

        return $response;
    }

    static function existsItem($cidade)
    {
        return Cidade::where('cidade', $cidade)->exists();
    }

    static function updateItem($id_cidade, $cidade){
        $update = false;

        $update = Cidade::where('id_cidade', $id_cidade)->update([
            'cidade'    => $cidade->cidade  , 
            'id_estado' => $cidade->idEstado,
            'id_ibge'   => $cidade->idIbge  ,
            'id_regiao' => $cidade->idRegiao,
            'situacao'  => $cidade->situacao,
            'id_porte'  => $cidade->idPorte ,
            'capital'   => $cidade->capital ,
            'populacao' => $cidade->populacao
        ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Cidade::where('id_cidade', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }      

    static function deleteItem($id){
        try {
            $delete = Cidade::where('id_cidade', $id)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    }        
}

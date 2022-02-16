<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Util\Util;


class TabelaPreco extends Model
{
    protected $table = 'TabelaPreco';

    public $timestamps = false;

    protected $fillable = [
        'id_tabPreco',
        'id_empresa',
        'descricao',
        'dt_val_ini',
        'dt_val_fim',
        'situacao'
    ];


    static function index($top = 10000, $id_empresa=null){
        $tabelaPreco = TabelaPreco::join('Cadastro' , 'TabelaPreco.id_empresa', '=', 'Cadastro.id_cadastro')
                            ->take($top)
                            ->lock('WITH(NOLOCK)')
                            ->when(isset($id_empresa), function ($query) use($id_empresa){
                                return $query->where('id_empresa', $id_empresa);
                             })                            
                            ->selectRaw("TabelaPreco.id_tabPreco,
                                         TabelaPreco.descricao,
                                         TabelaPreco.dt_val_ini dtValIni,
                                         TabelaPreco.dt_val_fim dtValFim,
                                         TabelaPreco.situacao, 
                                         Cadastro.fantasia, 
                                         (SELECT COUNT(*) 
                                            FROM PrecoProduto WITH(NOLOCK) 
                                           WHERE PrecoProduto.id_tabPreco = TabelaPreco.id_tabPreco
                                             AND situacao = 'A') qt_itens")
                            ->get();
        return $tabelaPreco;
    }    

    static function getById($id){
        $tabelaPreco = TabelaPreco::where('id_tabPreco', $id)
                                    ->lock('WITH(NOLOCK)')
                                    ->first();
        return $tabelaPreco;
    }

    static function getByEmpresa($id){
        $tabelaPreco = TabelaPreco::where('id-empresa', $id)
                                    ->lock('WITH(NOLOCK)');
        return $tabelaPreco;
    }

    static function store($id, $tabelaPreco)
    {
        $response = false;
        $response = TabelaPreco::insertGetId([
            'id_empresa' => $id,
            'descricao'  => $tabelaPreco->descricao,
            'dt_val_ini' => $tabelaPreco->dtValIni ,
            'dt_val_fim' => $tabelaPreco->dtValFim ,
            'situacao'   => $tabelaPreco->situacao
        ]);

        return $response;
    }

    static function updateItem($id_tabPreco, $tabelaPreco){
        $update = false;
            
        $update = TabelaPreco::where('id_tabPreco', $id_tabPreco)->update([
            'descricao'  => $tabelaPreco->descricao,
            'dt_val_ini' => $tabelaPreco->dtValIni ,
            'dt_val_fim' => $tabelaPreco->dtValFim ,
            'situacao'   => $tabelaPreco->situacao
        ]);

        return $update;
    }

    static function deleteItem($id_tabPreco){
        try {
        $delete = TabelaPreco::where('id_tabPreco', $id_tabPreco)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = TabelaPreco::where('id_tabPreco', $id)
                         ->update([
                            'situacao' => 'X'
                         ]);

        return $update;
    }   
    
    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }      

    private function requestValidate($request){
        $request->validate([
            'descricao' => ['required'],
            'situacao'  => ['required']
        ]);


        return true;
    }   
}

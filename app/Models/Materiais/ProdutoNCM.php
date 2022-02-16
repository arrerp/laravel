<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoNCM extends Model
{
    protected $table = 'ProdutoNCM';

    public $timestamps = false;

    protected $fillable = [
        'id_ncm',
        'ncm',
        'descricao',
        'situacao'
    ];


    static function store($request){
        $response = false;

        if(!ProdutoNCM::existsItem($request->ncm)){
            $response = ProdutoNCM::insertGetId([
                'ncm' => $request->ncm,
                'descricao' => $request->descricao,
                'situacao' => $request->situacao
            ]);
        }

        return $response;
    }

    static function existsItem($ncm){
        return ProdutoNCM::where('ncm', $ncm)->exists();
    }

    static function index($top = 1000){

        return ProdutoNCM::take($top)
                         ->lock('WITH(NOLOCK)')
                         ->selectRaw("ProdutoNCM.id_ncm, 
                                      ProdutoNCM.ncm, 
                                      ProdutoNCM.descricao, 
                                      CASE WHEN ProdutoNCM.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END situacao 
                            ")
                            ->orderBy('ProdutoNCM.id_ncm', 'asc')
                         ->get();

    }


    static function updateSituacao($id){
        $update = false;
            
        $update = ProdutoNCM::where('id_ncm', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }

    static function deleteItem($id_ncm){
        try {
        $delete = ProdutoNCM::where('id_ncm', $id_ncm)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }



    static function updateItem($id_ncm, $request){
        $update = false;
            
        $update = ProdutoNCM::where('id_ncm', $id_ncm)
                            ->update([
                                    'descricao' => $request->descricao                          
        ]);

        return $update;
    }


    
}

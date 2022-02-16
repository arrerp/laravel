<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Models\Materiais\Produto;
use App\Util\Util;

class PrecoProduto extends Model
{
    protected $table = 'PrecoProduto';

    public $timestamps = false;

    protected $fillable = [
        'id_precoProduto',
        'id_tabPreco',
        'id_produto',
        'vl_unitario',
        'pr_desc_apli',
        'dt_registro',
        'situacao', 
        'vl_precoDE', 
        'pontos'
    ];

    static function index($top = 10000, $id_tabPreco=null){
        $precoProduto = PrecoProduto::join('Produto'    , 'PrecoProduto.id_produto' , '=', 'Produto.id_produto')
                                    ->join('TabelaPreco', 'PrecoProduto.id_tabPreco', '=', 'TabelaPreco.id_tabPreco')
                            ->take($top)
                            ->lock('WITH(NOLOCK)')
                            ->when(isset($id_tabPreco), function ($query) use($id_tabPreco){
                                return $query->where('PrecoProduto.id_tabPreco', $id_tabPreco);
                             })                            
                            ->selectRaw("TabelaPreco.id_tabPreco , 
                                         TabelaPreco.descricao   ,
                                         Produto.SKU             , 
                                         Produto.descricao       ,
                                         PrecoProduto.vl_unitario, 
                                         PrecoProduto.dt_registro, 
                                         PrecoProduto.situacao   , 
                                         PrecoProduto.id_precoProduto, 
                                         PrecoProduto.vl_precoDE ,
                                         PrecoProduto.pontos")
                            ->get();
        return $precoProduto;
    }    

    static function getById($id){
        $precoProduto = PrecoProduto::where('id_precoProduto', $id)
                                    ->lock('WITH(NOLOCK)')
                                    ->first();
        return $precoProduto;
    }

    static function getByIdProduto($idProduto, $tabPreco){
        $precoProduto = PrecoProduto::where('id_produto', $idProduto)
                                    ->where('id_tabPreco', $tabPreco)
                                    ->lock('WITH(NOLOCK)')
                                    ->first();
        return $precoProduto;
    }


    static function store($idTabPreco, $precoProduto)
    {
        $response = false;
        $response = PrecoProduto::insertGetId([
            'id_tabPreco'  => $idTabPreco,
            'id_produto'   => $precoProduto->idProduto,
            'vl_unitario'  => Util::formataMoedaDB($precoProduto->vlUnitario),
            'pr_desc_apli' => Util::formataMoedaDB($precoProduto->prDescApli),
            'dt_registro'  => Now('America/Fortaleza'), 
            'situacao'     => $precoProduto->situacao, 
            'vl_precoDE'   => Util::formataMoedaDB($precoProduto->vlPrecoDE),
            'pontos'       => $precoProduto->pontos
        ]);

        return $response;
    }

    static function updateItem($id_precoProduto, $precoProduto){
        $update = false;
            
        $update = PrecoProduto::where('id_precoProduto', $id_precoProduto)->update([
            'vl_unitario'  => Util::formataMoedaDB($precoProduto->vlUnitario),
            'pr_desc_apli' => Util::formataMoedaDB($precoProduto->prDescApli),
            'dt_registro'  => Now('America/Fortaleza'), 
            'situacao'     => $precoProduto->situacao, 
            'vl_precoDE'   => Util::formataMoedaDB($precoProduto->vlPrecoDE),
            'pontos'       => $precoProduto->pontos            
        ]);

        return $update;
    }

    static function deleteItem($id_precoProduto){
        try {
            $delete = PrecoProduto::where('id_precoProduto', $id_precoProduto)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex->getMessage(); 
            // Note any method of class PDOException can be called on $ex.
        }
        return $delete;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = PrecoProduto::where('id_precoProduto', $id)
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
            'vlUnitario' => ['required'],
            'situacao'   => ['required']
        ]);

        return true;
    }   

    static function getByTabId($id_tabPreco, $top = 10){
        $precoProduto = Produto::take($top)
                                ->lock('WITH(NOLOCK)')
                                ->whereRaw("Produto.id_produto NOT IN(SELECT PrecoProduto.id_produto
                                                                        FROM PrecoProduto WITH(NOLOCK)
                                                                    WHERE PrecoProduto.id_tabPreco = $id_tabPreco)")
                                ->selectRaw("Produto.id_produto,
                                             RTRIM(Produto.SKU) + ' - ' + RTRIM(Produto.descricao) as descricao, 
                                             Produto.descricao as ordemDesc") 
                                ->orderBy('ordemDesc')                                
                                ->get();
        return $precoProduto;
    }        
}

<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoFichaValor extends Model
{
    protected $table = 'ProdutoFichaValor';

    public $timestamps = false;

    protected $fillable = [
        'id_produto',
        'id_fichaValor'
    ];
    
    static function index($idProduto){
        $fichas = ProdutoFichaValor::where('ProdutoFichaValor.id_produto', $idProduto)
                                   ->join('FichaTecnicaValor', 'FichaTecnicaValor.id_fichaValor', '=', 'ProdutoFichaValor.id_fichaValor')
                                   ->join('ProdutoFichaTecnica', 'FichaTecnicaValor.id_fichaTecnica', '=', 'ProdutoFichaTecnica.id_fichaTecnica')
                                   ->lock('WITH(NOLOCK)')
                                   ->get();

        return $fichas;
    }

    static function store($request)
    {
        $response = false;

        if(!ProdutoFichaValor::existsItem($request)){
            $response = ProdutoFichaValor::insertGetId([
                'id_produto' => $request->idProduto,
                'id_fichaValor' => $request->idFichaValor
            ]);
        }

        return $response;
    }

    static function deleteAllItem($idProduto){
        
        $delete = ProdutoFichaValor::where('id_produto', $idProduto)->delete();

        return $delete;
    }


    static function existsItem($request)
    {
        return ProdutoFichaValor::where('id_produto', $request->idProduto)
                                ->where('id_fichaValor', $request->idFichaValor)
                                ->exists();
    }
}

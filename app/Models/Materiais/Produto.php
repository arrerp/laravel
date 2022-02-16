<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Util\Util;

class Produto extends Model
{
    public $timestamps = false;

    protected $table = 'Produto';

    protected $fillable = [
        'SKU',
        'descricao',
        'informacoes',
        'SKU_PAI',
        'EAN',
        'dt_cadastro',
        'dt_ult_alt',
        'situacao',
        'prazo_ent_add',
        'id_prazo',
        'frete_gratis',
        'aceita_troca',
        'altura_cm',
        'largura_cm',
        'comprimento_cm',
        'ml',
        'peso_bruto',
        'peso_liquido',
        'garantia_dia',
        'fator_unidade',
        'valido_site',
        'exibir_site',
        'permitir_ss',
        'qtdeCompraMax',
        'qtdeCompraMin',
        'url_video',
        'referencia',
        'id_condicao',
        'id_unidade',
        'id_fabricante',
        'id_familia'
    ];

    static function index($top = 1000){
        $produtos = Produto::take($top)->lock('WITH(NOLOCK)')->get();

        $produtos = Produto::take($top)
                                      ->join('ProdutoFabricante', 'Produto.id_fabricante', '=', 'ProdutoFabricante.id_fabricante')
                                      ->lock('WITH(NOLOCK)')
                                      ->selectRaw('Produto.*, ProdutoFabricante.fabricante')
                                      ->orderByDesc('Produto.descricao')
                                      ->get();           

        return $produtos;
    }

    static function getWhere($sku){
        $produto = Produto::where('SKU', $sku)
                          ->lock('WITH(NOLOCK)')
                          ->first();

        return $produto;
    }

    static function getById($id_produto){
        $produto = Produto::where('id_produto', $id_produto)
                          ->lock('WITH(NOLOCK)')
                          ->first();

        return $produto;
    }

    static function getAtivos($top = 1000){
        $produtos = Produto::where('situacao', 'A')
                           ->lock('WITH(NOLOCK)')
                           ->take($top)
                           ->get();

        return $produtos;
    }

    static function getComposicao($top = 1000){
        $produtos = Produto::where('situacao', 'A')
                           ->join('ProdutoComposicao', 'ProdutoComposicao.id_produtoOri', '=', 'Produto.id_produto')
                           ->select(['id_produto', 'SKU', 'descricao'])
                           ->lock('WITH(NOLOCK)')
                           ->take($top)
                           ->distinct()
                           ->get();

        return $produtos;
    }

    static function getCompostos($top = 1000){
        $produtos = Produto::where('situacao', 'A')
                           ->rightJoin('ProdutoComposicao', 'ProdutoComposicao.id_produtoDes', '=', 'Produto.id_produto')
                           ->select(['id_produto', 'SKU', 'descricao'])
                           ->lock('WITH(NOLOCK)')
                           ->take($top)
                           ->distinct()
                           ->get();

        return $produtos;
    }

    static function store($produto)
    {
        $response = false;

        if(!Produto::existsItem($produto->sku)){
            $response = Produto::insertGetId([
                'SKU' => $produto->sku,
                'descricao' => $produto->descricao,
                'informacoes' => $produto->informacoes,
                'SKU_PAI' => $produto->skuPai,
                'EAN' => $produto->ean,
                'dt_cadastro' => Now('America/Fortaleza'),
                'dt_ult_alt' => Now('America/Fortaleza'),
                'situacao' => $produto->situacao,
                'prazo_ent_add' => $produto->prazoEntAdd,
                'id_prazo' => $produto->idPrazo,
                'frete_gratis' => $produto->freteGratis ?? 'N',
                'aceita_troca' => $produto->aceita_troca ?? 'N',
                'altura_cm' => $produto->alturaCm,
                'largura_cm' => $produto->larguraCm,
                'comprimento_cm' => $produto->comprimentoCm,
                'peso_bruto' => $produto->pesoBruto,
                'peso_liquido' => $produto->pesoLiquido,
                'garantia_dia' => $produto->garantiaDia ?? 0,
                'fator_unidade' => $produto->fatorUnidade,
                'valido_site' => $produto->validoSite ?? 'S',
                'exibir_site' => $produto->exibirSite ?? 'N',
                'permitir_ss' => $produto->permitirSs ?? 'N',
                'qtdeCompraMax' => $produto->qtdeCompraMax ?? 500,
                'qtdeCompraMin' => $produto->qtdeCompraMin ?? 1,
                'referencia' => $produto->referencia,
                'id_condicao' => $produto->idCondicao,
                'id_unidade' => $produto->idUnidade,
                'id_fabricante' => $produto->idFabricante,
                'id_familia' => $produto->idFamilia,
                'usa_lote'   => $produto->usaLote,
                'id_ncm'     => $produto->idNcm
            ]);
        }

        return $response;
    }

    static function existsItem($sku)
    {
        return Produto::where('sku', $sku)->exists();
    }

    static function updateItem($produto){
        $update = false;

        if(!Produto::existsItem($produto->idProduto)){
            $update = Produto::where('id_produto', $produto->idProduto)->update([
                'descricao' => $produto->descricao,
                'informacoes' => $produto->informacoes,
                'SKU_PAI' => $produto->skuPai,
                'EAN' => $produto->ean,
                'dt_cadastro' => Now('America/Fortaleza'),
                'dt_ult_alt' => Now('America/Fortaleza'),
                'situacao' => $produto->situacao,
                'prazo_ent_add' => $produto->prazoEntAdd,
                'id_prazo' => $produto->idPrazo,
                'frete_gratis' => $produto->freteGratis ?? 'N',
                'aceita_troca' => $produto->aceita_troca ?? 'N',
                'altura_cm' => $produto->alturaCm,
                'largura_cm' => $produto->larguraCm,
                'comprimento_cm' => $produto->comprimentoCm,
                'peso_bruto' => $produto->pesoBruto,
                'peso_liquido' => $produto->pesoLiquido,
                'garantia_dia' => $produto->garantiaDia ?? 0,
                'fator_unidade' => $produto->fatorUnidade,
                'valido_site' => $produto->validoSite ?? 'S',
                'exibir_site' => $produto->exibirSite ?? 'N',
                'permitir_ss' => $produto->permitirSs ?? 'N',
                'qtdeCompraMax' => $produto->qtdecompramax ?? 500,
                'qtdeCompraMin' => $produto->qtdecompramax ?? 1,
                'referencia' => $produto->referencia,
                'id_condicao' => $produto->idCondicao,
                'id_unidade' => $produto->idUnidade,
                'id_fabricante' => $produto->idFabricante,
                'id_familia' => $produto->idFamilia,
                'usa_lote'   => $produto->usaLote
            ]);
        }

        return $update;
    }

    static function deleteItem($id_produto){

        $delete = Produto::where('id_produto', $id_produto)->delete();

        return $delete;
    }

    static function getTableInfo(){
        $model = new self();

        return Util::getTableInfo($model);
    }

    static function buscaProduto($busca){
        $produtos = Produto::where('descricao', 'LIKE', '%'.$busca.'%')
                            ->lock('WITH(NOLOCK)')
                            ->selectRaw("Produto.id_produto,
                                        Produto.SKU,
                                        Produto.descricao")
                            ->take(3)
                            ->get();

        return $produtos;
    }
    
}

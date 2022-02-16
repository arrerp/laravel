<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Models\Materiais\Produto;

class ProdutoRascunho extends Model
{
    public $timestamps = false;

    protected $table = 'ProdutoRascunho';

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
        $produtos = ProdutoRascunho::take($top)->lock('WITH(NOLOCK)')->get();

        return $produtos;
    }

    static function getWhere($sku){
        $produto = ProdutoRascunho::where('SKU', $sku) 
                                  ->lock('WITH(NOLOCK)')
                                  ->first();

        return $produto;
    }

    static function store($produto)
    {
        $response = false;

        if(!ProdutoRascunho::existsItem($produto->sku)){
            $response = ProdutoRascunho::insertGetId([
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
                'id_familia' => $produto->idFamilia
            ]);
        }

        return $response;
    }

    static function existsItem($sku)
    {
        $rascunho = ProdutoRascunho::where('sku', $sku)->exists();
        $produto = Produto::where('sku', $sku)->exists();

        if($rascunho || $produto){
            return true;
        
        } else {
            return false;
        }
    }

    static function existsRascunho($sku){
        $produto = Produto::where('sku', $sku)->exists();

        return $produto;
    }

    static function updateItem($produto){
        $update = false;

        if(!ProdutoRascunho::existsRascunho($produto->sku)){
            $update = ProdutoRascunho::where('sku', $produto->sku)->update([
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
                'id_familia' => $produto->idFamilia
            ]);
        }

        return $update;
    }

    static function deleteItem($sku){
        
        $delete = ProdutoRascunho::where('sku', $sku)->delete();

        return $delete;
    }


}

<?php

namespace App\Http\Controllers\Materiais;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Materiais\Deposito;
use App\Models\Cadastros\Empresa;
use App\Models\Materiais\TransformaProduto;
use App\Models\Materiais\Produto;
use App\Models\Materiais\Procedures\spcSugereLoteItem;
use App\Util\Util;


class TransformaProdutoController extends Controller
{
    public function getPageInfo(Request $request){
        $empresas       = Empresa::index();
        $depositos      = Deposito::index();
        $produtos       = Produto::getComposicao();
        $produtosOrigem = Produto::getCompostos();
        $produtosOrigem = $this->SKU_Descricao($produtosOrigem);
        $produtos       = $this->SKU_Descricao($produtos);
        
        $response = [
            'empresas'  => Util::toSelectKeys($empresas , 'fantasia', 'id_empresa'),
            'depositos' => Util::toSelectKeys($depositos, 'descricao', 'id_deposito', ['id_empresa']),
            'produtos' => Util::toSelectKeys($produtos, 'descricao', 'id_produto'),
            'produtosOrigem' => Util::toSelectKeys($produtosOrigem, 'descricao', 'id_produto'),
        ];

        return response()->json($response, 200);        
    }

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $movimentos = TransformaProduto::index($top);

        return response()->json($movimentos, 200);
    }

    public function store(Request $request){
        $request->validate([
            'idEmpresa'      => ['required'],
            'idDepositoOri'  => ['required'],
            'idDepositoDes'  => ['required'],
            'itens'          => ['required'],
        ]);

        $request = json_decode($request->getContent());

        $httpCode = 200;

        $response = true;
    }


    public function destroy($id){
        $delete = TransformaProduto::destroy($id);

        return response()->json($delete, 200);
    }

    public function sugereLote(Request $request){
        $params = [
            $request->empresa, 
            $request->deposito,
            $request->idProduto,
            $request->quantidade,
        ];

        $lotes = spcSugereLoteItem::exec($params);
        $lotes = $this->maskColumnsLote($lotes);

        return response()->json($lotes, 200);
    }

    private function maskColumnsLote($lotes){
        $maskLotes = [];

        foreach($lotes as $lote){
            $maskLotes[] = [
                'nrLote' => $lote->nr_lote,
                'fabricacao' => $lote->dt_fabricacao,
                'validade' => $lote->dt_validade,
                'saldo' => $lote->qt_saldo,
                'quantidade' => $lote->qt_indicada,
            ];
        }

        return $maskLotes;
    }

    private function SKU_Descricao($produtos){
        $skuProduto = [];

        foreach($produtos as $produto){
            $skuProduto[] = [
                'descricao'  => $produto->descricao . ' (' . $produto->SKU . ')',
                'id_produto' => $produto->id_produto
            ];
        }

        return $skuProduto;
    }
}

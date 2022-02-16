<?php

namespace App\Http\Controllers\Materiais;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Materiais\Deposito;
use App\Models\Cadastros\Empresa;
use App\Models\Materiais\MovimentoEstoque;
use App\Models\Materiais\MovtoEstItem;
use App\Models\Materiais\MovtoEstItemLote;
use App\Models\Materiais\Produto;
use App\Models\Materiais\TipoMovimento;
use App\Models\Materiais\Procedures\spcSugereLoteItem;
use App\Util\Util;



class MovimentoEstoqueController extends Controller
{
    public function getPageInfo(Request $request){
        $empresas   = Empresa::index();
        //$idEmpresa  = $empresas[0]->id_empresa ?? 0;
        //$depositos  = Deposito::getByIdEmp($idEmpresa);
        $depositos  = Deposito::index();
        $movimentos = TipoMovimento::index();
        $produtos   = Produto::index();
        $produtos   = $this->SKU_Descricao($produtos);
        $columns    = MovimentoEstoque::getTableInfo();
        
        $response = [
            'empresas'       => Util::toSelectKeys($empresas  , 'fantasia', 'id_empresa'),
            'tipoMovimentos' => Util::toSelectKeys($movimentos, 'descricao', 'id_tipoMovimento', ['sinal', 'traf_deposito']),
            'depositos'      => Util::toSelectKeys($depositos , 'descricao', 'id_deposito', ['id_empresa']),
            'produtos'       => Util::toSelectKeys($produtos  , 'descricao', 'id_produto'),
            'columns'        => $columns,
        ];

        return response()->json($response, 200);        
    }

    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $movimentos = MovimentoEstoque::index($top);

        return response()->json($movimentos, 200);
    }

    public function getItensByMovimento($id){
        $itens = MovtoEstItem::getItensByMovimento($id);

        $itens = $this->serializeItensWithLotes($itens, $id);

        return response()->json($itens, 200);

    }


    public function store(Request $request){
        
        $request->validate([
            'idEmpresa'         => ['required'],
            'idMovimento'       => ['required'],
            'idDepositoOrigem'  => ['required'],
            'nrDocRef'          => ['required'],
            'itens'             => ['required'],
        ]);

        $request = json_decode($request->getContent());

        $httpCode = 200;

        $response = true;

        DB::beginTransaction();

        $tipoMovimento = TipoMovimento::getById($request->idMovimento);

        for($i = 0; $i < $this->getNumIterations($tipoMovimento); $i++){
            if($i === 1){
                $idDepositoOrigem = $request->idDepositoOrigem;
                $request->idMovimento = $request->idMovimentoDestino;
                $request->idDepositoOrigem = $request->idDepositoDestino;
                $request->idDepositoDestino = $idDepositoOrigem;

            }

            $idMovimento = MovimentoEstoque::store($request);

            if(!$idMovimento){
                $response = [
                    'errors' => [
                        'movimento' => ['Ops, houve algum erro']
                    ]
                ];
    
                $httpCode = 402;
    
                DB::rollBack();
    
                return response()->json($response, $httpCode);
            }
    
            $itens = $request->itens;
    
            foreach($itens as $item){
                if(count($item->lotes) > 0){
                    $usaLote = 'S';
                } else {
                    $usaLote = 'N';
                }
    
                $insertItem = [
                    'id_movimento' => $idMovimento,
                    'id_produto' => $item->idItem,
                    'qt_movimento' => $item->quantidade,
                    'observacao' => $item->observacao,
                    'usa_lote' => $usaLote,
                ];
    
                $idMovimentoItem = MovtoEstItem::store($insertItem);
    
                if(!$idMovimentoItem){
                    DB::rollBack();
    
                    $response = false;
                
                } else if($usaLote === 'S'){
                    $lotes = $item->lotes;
    
                    foreach($lotes as $lote){
                        $insertLote = [
                            'id_movtoItem' => $idMovimentoItem,
                            'id_produto' => $item->idItem,
                            'nr_lote' => $lote->nrLote,
                            'dt_validade' => $lote->validade,
                            'qt_lote' => $lote->quantidade,
                            'dt_fabricacao' => $lote->fabricacao,
                        ];
    
                        $responseLote = MovtoEstItemLote::store($insertLote);
    
                        if(!$responseLote){
                            DB::rollBack();
    
                            $response = [
                                'errors' => [
                                    'movimento' => ['Ops, houve algum erro']
                                ]
                            ];
                
                            $httpCode = 402;
                        
                    
                            return response()->json($response, $httpCode);
                        }
                    }
                }
            }
        }

        DB::commit();
        return response()->json($response, $httpCode);
    }


    public function destroy($id){
        $delete = MovtoEstItemLote::destroy($id);

        if($delete){
            $delete = MovtoEstItem::destroy($id);

            if($delete){
                $delete = MovimentoEstoque::destroy($id);
            }
        }

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

    private function getNumIterations($tipoMovimento){
        if(($tipoMovimento->sinal === 'E' && $tipoMovimento->traf_deposito === 'S') || $tipoMovimento->sinal === 'S'){
            $numIterations = 2;
        
        } else {
            $numIterations = 1;
        }

        return $numIterations;
        
    }

    private function serializeItensWithLotes($itens, $id){
        foreach($itens as $item){
            $lotes = MovtoEstItemLote::getLotesProduto($id, $item->id_produto);
                
            $item->lotes = $lotes;
        }

        return $itens;

    }
}

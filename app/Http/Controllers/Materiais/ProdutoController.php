<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\CondicaoProduto;
use App\Models\Materiais\PrazoProduto;
use App\Models\Materiais\PrecoProduto;
use App\Models\Materiais\Produto;
use App\Models\Materiais\ProdutoRascunho;
use App\Models\Materiais\ProdutoFabricante;
use App\Models\Materiais\ProdutoFamilia;
use App\Models\Materiais\ProdutoUnidade;
use App\Models\Materiais\ProdutoCategoria;
use App\Models\Materiais\ProdutoCategoriaItem;
use App\Models\Materiais\ProdutoComposicao;
use App\Models\Materiais\ProdutoFichaTecnica;
use App\Models\Materiais\ProdutoFichaValor;
use App\Models\Materiais\ProdutoImagem;
use App\Models\Materiais\ProdutoNCM;
use App\Models\Materiais\Views\VSaldoItemDispSite;
use App\Util\Util;

class ProdutoController extends Controller
{
    public function getPageInfo(Request $request){
        $condicoes   = CondicaoProduto::index();
        $fabricantes = ProdutoFabricante::index();
        $familias    = ProdutoFamilia::index();
        $prazos      = PrazoProduto::index();
        $unidades    = ProdutoUnidade::index();
        $categorias  = ProdutoCategoria::getAtivos();
        $fichas      = ProdutoFichaTecnica::getAtivos();
        $produtos    = Produto::getAtivos();
        $composicao  = Produto::getComposicao();
        $columns     = Produto::getTableInfo();
        $produtoNCM     = ProdutoNCM::index();

        $response = [
            'condicoes'   => Util::toSelectKeys($condicoes, 'descricao', 'id_condicao'),
            'fabricantes' => Util::toSelectKeys($fabricantes, 'fabricante', 'id_fabricante'),
            'familias'    => Util::toSelectKeys($familias, 'familia', 'id_familia'),
            'prazos'      => Util::toSelectKeys($prazos, 'descricao', 'id_prazo'),
            'unidades'    => Util::toSelectKeys($unidades, 'descricao', 'id_unidade'),
            'produtos'    => Util::toSelectKeys($produtos, 'descricao', 'id_produto', ['SKU']),
            'categorias'  => $categorias,
            'fichas'      => $fichas,
            'columns'     => $columns,
            'produtoncm'  => Util::toSelectKeys($produtoNCM, 'descricao', 'id_ncm')
        ];

        return response()->json($response, 200);
    }

    public function index(Request $request){
        $produtos  = Util::toArray(Produto::index());
        $rascunhos = Util::toArray(ProdutoRascunho::index());

        $all = array_merge($produtos, $rascunhos);
        $serialized = [];

        foreach($all as $produto){
            $p = (object)$produto;
            $caminho_img = null;

            if($p->id_produto !== null){
                $img = ProdutoImagem::getMainById($p->id_produto);
                $caminho_img =  '/' . $img['caminho_img'];
            }

            $produto['caminho_img'] = $caminho_img;

            $serialized[] = $produto;
        }

        return response()->json($serialized, 200);
    }

    public function getProduto($sku){
        $rascunho = ProdutoRascunho::existsRascunho($sku);
        $definitivo = Produto::existsItem($sku);

        if($rascunho && !$definitivo){
            $produto = ProdutoRascunho::getWhere($sku);

        } else {
            $produto = Produto::getWhere($sku);
        }

        return response()->json($produto, 200);
    }

    public function getBySku($sku){
        $produto = ProdutoRascunho::getWhere($sku);

        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $produto = Util::varcharToBoolean(Util::toArray($produto), true, $customVars);

        return response()->json($produto, 200);
    }

    public function getById($id){
        $produto = Produto::getById($id);

        $customVars = [
            'true' => [
                'A'
            ],
            'false' => [
                'X',
                'C'
            ]
        ];

        $produto = Util::varcharToBoolean(Util::toArray($produto), true, $customVars);

        $categorias = $this->serializeCategorias($id);
        $fichas = $this->serializeFichaTecnica($id);
        $composicao = $this->serializeComposicao($id);

        $produto->categorias = $categorias;
        $produto->fichas = $fichas;
        $produto->composicao = $composicao;

        return response()->json($produto, 200);
    }

    public function getImagesById($id){
        $images = ProdutoImagem::getById($id);

        foreach($images as $image){
            if($image->caminho_img){
                $imgInfo = [
                    'id' =>  $image->id_imagem,
                    'src' =>  '/' . $image->caminho_img,
                    'name' => $image->nome_arq,
                    'main' => $image->img_apresentacao
                ];

                $imagesBase64[] = $imgInfo;
            }
        }

        return response()->json($imagesBase64);
    }

    public function store(Request $request){
        $httpCode = 200;

        if($request->rascunho){
            $modo = 'Rascunho';
            $produto =  $this->validaRascunho($request);
            $response = $this->storeRascunho($produto);

        } else {
            $modo = 'Produto';
            $produto = $this->validaProduto($request);
            $response = $this->storeProduto($produto);
        }

        if(!$response){
            $response = [
                'errors' => [
                    'produto' => [$modo . ' já cadastrado']
                ]
            ];

            $httpCode = 402;

        } else {
            $this->storeCategorias($response, $request->categorias);

            $response = [
                'message' => $modo . ' cadastrado com sucesso'
            ];
        }

        return response()->json($response, $httpCode);
    }

    public function storeImages(Request $request){
        $res = ProdutoImagem::deleteAll($request->id);

        if(!$request->hasFile('images')){
            return;
        }

        $main = $request->main;

        $imagens = $request->file('images');

        $path = '/' . $request->sku;

        $i = 0;
        foreach($imagens as $img){

            // Nome original do arquivo
            $nome_arquivo = $img->getClientOriginalName();
            $caminho = 'imagens/produtos' . $path . '/' . $nome_arquivo;

            $obj = (object)[];

            $obj->idProduto = $request->id;
            $obj->nomeArq = $nome_arquivo;
            $obj->caminhoImg = $caminho;
            $obj->imgApresentacao = $main[$i];

            ProdutoImagem::store($obj);

            // Método para salvar o arquivo no disco
            $img->storeAs($path, $nome_arquivo, 'imgProduto');
            $i++;
        }

        return response()->json($res, 200);
    }

    public function delete($id){
        $httpCode = 200;

        $response = Produto::deleteItem($id);

        if(!$response){
            $response = [
                'errors' => [
                    'produto' => ['Não foi possível excluir o produto']
                ]
            ];

            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }

    public function update(Request $request){
        $httpCode = 200;

        $this->storeCategorias($request->idProduto, $request->categorias);
        $this->storeFichaTecnica($request->idProduto, $request->fichas);
        $this->storeComposicao($request->idProduto, $request->composicao);

        if($request->rascunho){
            $modo = 'Rascunho';
            $produto =  $this->validaRascunho($request);
            $response = $this->updateRascunho($produto);

        } else {
            $modo = 'Produto';
            $produto = $this->validaProduto($request);
            $response = $this->updateProduto($produto);
        }

        if(!$response){
            $response = [
                'errors' => [
                    'produto' => ['Não foi possível alterar o ' . strtolower($modo)]
                ]
            ];

            $httpCode = 402;

        }

        return response()->json($response, $httpCode);
    }

    public function deleteRascunho($sku){
        $response = ProdutoRascunho::deleteItem($sku);

        return $response;
    }

    public function deleteProduto($id){
        $response = Produto::deleteItem($id);

        return $response;
    }

    public function getProdutoSite($id){
        if(!isset($id)){
            return;
        }

        $id = 25;

        $produto = Produto::getById($id);

        $response = $this->serializeProdutoResponse($produto);

        return response()->json($response, 200);
    }

    private function serializeProdutoResponse($produto){
        $fabricante = ProdutoFabricante::getById($produto->id_fabricante);
        $active = $produto->situacao === 'A' ? true : false;
        $saldo = Util::formataSaldo(VSaldoItemDispSite::getSaldoItem(4, $produto->id_produto));
        $outOfStock = $saldo == 0 ? true : false;
        $precoItem = PrecoProduto::getByIdProduto($produto->id_produto, 5);
        $isSale = $produto->exibir_site === 'S' ? true : false;
        $images = $this->getSiteImages(27);

        $response = [
            'brands' => [],
            'collections' => [],
            'created_at' => "2020-03-15T06:42:37.444Z",
            'created_by' => null,
            'depot' => 70,
            'id' => $produto->id_produto,
            'images' => $images,
            'inventory' => $saldo,
            'is_active' => $active,
            'is_featured' => false,
            'is_hot' => false,
            'is_out_of_stock' => $outOfStock,
            'is_sale' => $isSale,
            'price' => (float)$precoItem->vl_unitario,
            'product_categories' => [],
            'review' => 4,
            'sale_price' => (float)$precoItem->vl_unitario,
            'slug' => null,
            'thumbnail' => [],
            'title' => $produto->descricao,
            'updated_at' => "2020-03-18T12:54:14.665Z",
            'updated_by' => null,
            'variants' => [],
            'vendor' => $fabricante->fabricante,
        ];

        return $response;

    }

    private function storeCategorias($id, $categorias){
        $response = false;

        ProdutoCategoriaItem::deleteAllItem($id);

        foreach($categorias as $categoria){
            $insert = (object)[
                'idProduto' => $id,
                'idCategoria' => $categoria['value']
            ];

            $response = ProdutoCategoriaItem::store($insert);
        }

        return $response;
    }

    private function storeFichaTecnica($id, $fichas){
        $response = false;

        ProdutoFichaValor::deleteAllItem($id);

        foreach($fichas as $ficha){
            $insert = (object)[
                'idProduto' => $id,
                'idFichaValor' => $ficha['value']
            ];

            $response = ProdutoFichaValor::store($insert);
        }

        return $response;
    }

    private function storeComposicao($id, $composicao){
        $response = false;

        ProdutoComposicao::deleteAllItem($id);

        foreach($composicao as $produto){
            $insert = (object)[
                'idProdutoOri' => $id,
                'idProdutoDes' => $produto['id'],
                'qtAplicada' => $produto['quantidade'],
            ];

            $response = ProdutoComposicao::store($insert);
        }

        return $response;
    }

    private function serializeCategorias($id){
        $categorias = Util::toObject(ProdutoCategoriaItem::index($id));
        $serialized = [];

        foreach($categorias as $categoria){
            $serialized[] = [
                'label' => $categoria->categoria,
                'value' => $categoria->id_categoria
            ];
        }

        return $serialized;
    }


    private function serializeFichaTecnica($id){
        $fichas = Util::toObject(ProdutoFichaValor::index($id));
        $serialized = [];

        foreach($fichas as $ficha){
            $serialized[] = [
                'label' => $ficha->valor,
                'value' => $ficha->id_fichaValor,
                'parent' => $ficha->fichaTecnica,
            ];
        }

        return $serialized;
    }

    private function serializeComposicao($id){
        $composicao = Util::toObject(ProdutoComposicao::getById($id));
        $serialized = [];

        foreach($composicao as $item){
            $serialized[] = [
                'id' => $item->id_produto,
                'sku' => $item->SKU,
                'descricao' => $item->descricao,
                'quantidade' => Util::formataSaldo($item->qt_aplicada),
            ];
        }

        return $serialized;
    }


    private function validaRascunho($request){
        $request->validate([
            'sku' => ['required']
        ]);

        $produto = $this->serializeRequest($request);

        return $produto;
    }

    private function validaProduto($request){
        $request->validate([
            'sku' => ['required'],
            'descricao' => ['required'],
            'ean' => ['required'],
            'prazoEntAdd' => ['required'],
            'alturaCm' => ['required'],
            'larguraCm' => ['required'],
            'comprimentoCm' => ['required'],
            'pesoBruto' => ['required'],
            'pesoLiquido' => ['required'],
            'garantiaDia' => ['required'],
            'fatorUnidade' => ['required'],
            'qtdecompramax' => ['required'],
            'qtdecompramax' => ['required'],
            'aceitaTroca' => ['required'],
            'situacao' => ['required'],
            'validoSite' => ['required'],
            'freteGratis' => ['required'],
            'permitirSs' => ['required'],
            'referencia' => ['required'],
            'exibirSite' => ['required'],
            'idPrazo' => ['required'],
            'idCondicao' => ['required'],
            'idUnidade' => ['required'],
            'idFabricante' => ['required'],
            'idFamilia' => ['required'],
            'usaLote'   => ['required']

        ]);

        $produto = $this->serializeRequest($request);

        return $produto;
    }

    private function storeRascunho($produto){
        $response = ProdutoRascunho::store($produto);

        return $response;
    }

    private function storeProduto($produto){
        ProdutoRascunho::deleteItem($produto->sku);

        $response = Produto::store($produto);

        return $response;
    }

    private function updateRascunho($produto){
        $response = ProdutoRascunho::updateItem($produto);

        return $response;
    }

    private function updateProduto($produto){
        $response = Produto::updateItem($produto);

        return $response;
    }

    private function serializeRequest($request){
        $requestArray = json_decode($request->getContent(), true);

        $produto = Util::booleanToVarchar($requestArray);

        if($produto->situacao === 'S'){
            $produto->situacao = 'A';

        } else {
            $produto->situacao = 'X';
        }

        return $produto;
    }

    public function buscaProduto($busca){
        $buscaProduto = Produto::buscaProduto($busca);
        $buscaProduto = Util::toSelectKeys($buscaProduto , 'descricao', 'id_produto' );
        return response()->json($buscaProduto, 200);
    }

    private function getSiteImages($produtoId){
        $siteImages = [];
        $images = ProdutoImagem::getById($produtoId);

        foreach($images as $image){
            $siteImages[] = [
                'ext' => $this->getFileExtension($image->caminho_img),
                'name' => $image->nome_arq,
                'url' => '.' . $image->caminho_img
            ];
        }

        return $siteImages;
    }

    private function getFileExtension($path){
        $temp = explode('.', $path);

        $extension = '.' . end($temp);

        return $extension;
    }
}

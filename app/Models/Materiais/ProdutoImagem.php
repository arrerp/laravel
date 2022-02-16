<?php

namespace App\Models\Materiais;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ProdutoImagem extends Model
{
    protected $table = 'ProdutoImagem';

    public $timestamps = false;

    protected $fillable = [
        'id_produto',
        'nome_arq',
        'caminho_img',
        'img_apresentacao',
    ];

    static function getById($id){
        $images = ProdutoImagem::where('id_produto', $id)->get();

        return $images;
    }

    static function getMainById($id){
        $images = ProdutoImagem::where('id_produto', $id)
                               ->where('img_apresentacao', 'S')
                               ->first();

        return $images;
    }

    static function store($request)
    {
        $response = false;

        if(!ProdutoImagem::existsItem($request)){
            $response = ProdutoImagem::insertGetId([
                'id_produto' => $request->idProduto,
                'nome_arq' => $request->nomeArq,
                'caminho_img' => $request->caminhoImg,
                'img_apresentacao' => $request->imgApresentacao,
            ]);
        }
        return $response;
    }

    static function deleteAll($id)
    {
        $images = ProdutoImagem::getById($id);

        foreach($images as $image){
            $path = str_replace('imagens/produtos/', '', $image->caminho_img);

            Storage::disk('imgProduto')->delete($path);
        }

        return ProdutoImagem::where('id_produto', $id)->delete();
    }


    static function existsItem($request)
    {
        return ProdutoImagem::where('id_produto', $request->idProduto)
                            ->where('nome_arq', $request->nomeArq)
                            ->exists();
    }
}

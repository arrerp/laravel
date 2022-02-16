<?php

namespace App\Models\Materiais;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovtoEstItem extends Model
{
    public $timestamps = false;

    protected $table = 'MovtoEstItem';

    protected $fillable = [
        'id_movimento',
        'id_produto',
        'qt_movimento',
        'observacao',
        'usa_lote',
    ];


    static function store($request){
        $id = MovtoEstItem::insertGetId($request);

        return $id;
    }

    static function getItensByMovimento($id){
        $itens = MovtoEstItem::join('Produto', 'MovtoEstItem.id_produto', 'Produto.id_produto')
                             ->where('MovtoEstItem.id_movimento', $id)
                             ->get();

        return $itens;
    }

    static function destroy($id){
        if(MovtoEstItem::existsItem($id)){
            try {
                $delete = MovtoEstItem::where('id_movimento', $id)
                                    ->delete();

            } catch(Exception $e){
                $delete = false;
            }

        } else {
            $delete = true;
        }

        return $delete;
    }

    static function existsItem($id){
        $exists = MovtoEstItem::where('id_movimento', $id)
                              ->exists();

        return $exists;
    }
}

<?php

namespace App\Models\Materiais;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovtoEstItemLote extends Model
{
    public $timestamps = false;

    protected $table = 'MovtoEstItemLote';

    protected $fillable = [
        'id_movtoItem',
        'id_produto',
        'nr_lote',
        'dt_validade',
        'qt_lote',
        'dt_fabricacao',
    ];

    static function store($request){
        $id = MovtoEstItemLote::insertGetId($request);

        return $id;
    }

    static function destroy($id){
        if(MovtoEstItemLote::existsLote($id)){
            try {
                $delete = false;

                $lotes = MovtoEstItemLote::getLotes($id);

                if($lotes){
                    foreach($lotes as $lote){
                       $delete = MovtoEstItemLote::where('id_movtoItemLote', $lote->id_movtoItemLote)
                                                 ->delete();
                    }
                }
            } catch(Exception $e){
                $delete = false;
            }
        
        } else {
            $delete = true;
        }

        return $delete;
    }

    static function getLotes($id){
        $lotes = MovtoEstItemLote::join('MovtoEstItem', 'MovtoEstItemLote.id_movtoItem', 'MovtoEstItem.id_movtoItem')
                                 ->selectRaw('MovtoEstItemLote.*')
                                 ->where('MovtoEstItem.id_movimento', $id)
                                 ->get();

        return $lotes;
    }

    static function getLotesProduto($id, $produto){
        $lotes = MovtoEstItemLote::join('MovtoEstItem', 'MovtoEstItemLote.id_movtoItem', 'MovtoEstItem.id_movtoItem')
                                 ->selectRaw('MovtoEstItemLote.*')
                                 ->where('MovtoEstItem.id_movimento', $id)
                                 ->where('MovtoEstItem.id_produto', $produto)
                                 ->get();

        return $lotes;
    }

    static function existsLote($id){
        $exists = MovtoEstItemLote::join('MovtoEstItem', 'MovtoEstItemLote.id_movtoItem', 'MovtoEstItem.id_movtoItem')
                                  ->where('MovtoEstItem.id_movimento', $id)
                                  ->exists();

        return $exists;
    }
}

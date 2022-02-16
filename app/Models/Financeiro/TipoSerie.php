<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class TipoSerie extends Model
{
    protected $table = 'TipoSerie';

    public $timestamps = false;

    protected $fillable = [
        'id_tpSerie',
        'descricao',
        'reduzido'
    ];


    static function index($top = 10000){
        $tipoSerie = TipoSerie::lock('WITH(NOLOCK)')
                      ->select(['id_tpSerie',
                                'descricao', 
                                'reduzido'])
                      ->get();
        return $tipoSerie;
    }

    static function getById($id_tpSerie){
        $tipoSerie = TipoSerie::where('id_tpSerie', $id_tpSerie)
                      ->lock('WITH(NOLOCK)')
                      ->first();

        return $tipoSerie;
    }

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }        
}

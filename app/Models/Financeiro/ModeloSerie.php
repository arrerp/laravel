<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class ModeloSerie extends Model
{
    protected $table = 'ModeloSerie';

    public $timestamps = false;

    protected $fillable = [
        'id_modelo',
        'descricao'
    ];


    static function index($top = 10000){
        $modeloSerie = ModeloSerie::lock('WITH(NOLOCK)')
                      ->select(['id_modelo',
                                'descricao'])
                      ->get();
        return $modeloSerie;
    }

    static function getById($id_modelo){
        $modeloSerie = ModeloSerie::where('id_modelo', $id_modelo)
                      ->lock('WITH(NOLOCK)')
                      ->first();

        return $modeloSerie;
    }

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }        
}

<?php

namespace App\Models\Customs;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $table = 'Models';

    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'model',
    ];

    static function index($model)
    {
        $columns =  Models::join('Models', 'ModelIndexColumn.id_model', 'Models.id_model')
                          ->where('model', $model)
                          ->get();

        return $columns;
    }
}

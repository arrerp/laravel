<?php

namespace App\Models\Customs;

use Illuminate\Database\Eloquent\Model;

class ModelIndexColumn extends Model
{
    protected $table = 'ModelIndexColumn';

    public $timestamps = false;

    protected $fillable = [
        'id_model',
        'column_name',
        'column_alias',
    ];

    static function getColumns($model)
    {
        $columns =  ModelIndexColumn::join('Models', 'ModelIndexColumn.id_model', 'Models.id_model')
                                    ->where('model', $model)
                                    ->get();

        return $columns;
    }

}

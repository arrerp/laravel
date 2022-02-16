<?php

namespace App\Models\Customs;

use Illuminate\Database\Eloquent\Model;

class ModelRoutes extends Model
{
    protected $table = 'ModelRoutes';

    public $timestamps = false;

    protected $fillable = [
        'id_model',
        'name',
        'route',
    ];

    static function index($model)
    {
        $routes =  ModelRoutes::join('Models', 'ModelRoutes.id_model', 'Models.id_model')
                              ->where('model', $model)
                              ->get();

        return $routes;
    }

}

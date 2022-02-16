<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    public $timestamps = false;

    protected $table = 'Models';

    protected $fillable = [
        'id_menu',
        'model'
    ];

    static function index(){
        $models = Models::get();

        return $models;
    }

    static function getModel($model){
        $model = Models::join('Menu', 'Models.id_menu', 'Menu.id_menu')
                       ->where('model', $model)
                       ->first();
                         
        return $model;
    }

    static function store($menuId, $modelName){
        $create = Models::create(['id_menu' => $menuId, 'model' => $modelName]);

        return $create;
    }
}

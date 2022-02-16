<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClasseEmail extends Model
{
    protected $table = 'ClasseEmail';

    public $timestamps = false;

    protected $fillable = [
        'id_classe',
        'descricao'
    ];

    static function index($top = 1000){

        return ClasseEmail::take($top)
                         ->lock('WITH(NOLOCK)')
                         ->get();

    }

    static function existsItem($descricao){
        return ClasseEmail::where('descricao', $descricao)->exists();
    }

    static function store($req)
    {

        $response = false;
        $response = ClasseEmail::insertGetId([
            'descricao' => $req->descricao
        ]);
        return $response;
    }

    static function deleteItem($id){
        try {
        $delete = ClasseEmail::where('id_classe', $id)->delete();
        } catch(\Illuminate\Database\QueryException $ex){
            $delete = $ex;
        }
        return $delete;
    }

    static function updateItem($id_classe, $request){
        $update = false;

        $update = ClasseEmail::where('id_classe', $id_classe)
                            ->update([
                                    'descricao' => $request->descricao
        ]);

        return $update;
    }

}

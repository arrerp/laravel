<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorteCidade extends Model
{
    protected $table = 'PorteCidade';

    public $timestamps = false;

    protected $fillable = [
        'id_porte',
        'porte'
    ];

    static function index($top = 1000){
        $porte = PorteCidade::take($top)
                            ->lock('WITH(NOLOCK)')
                            ->get()
                            ->toArray();

        return $porte;                              
    }

    static function getWhere($porte){
        $portes = PorteCidade::where('porte', $porte)->lock('WITH(NOLOCK)')->get();

        return $portes;
    }

    static function getById($id){
        $portes = PorteCidade::where('id_porte', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $portes;
    }

    static function store($porte)
    {
        $response = false;

        $response = PorteCidade::insertGetId([
            'porte'        => $porte->porte
        ]);

        return $response;
    }

    static function existsItem($porte)
    {
        return PorteCidade::where('porte', $porte)->exists();
    }

    static function updateItem($id_porte, $porte){
        $update = false;

        if(!PorteCidade::existsItem($porte->porte)){
            $update = PorteCidade::where('id_porte', $id_porte)->update([
                'porte'        => $porte->porte
            ]);
        }

        return $update;
    }

    static function deleteItem($id_porte){
        
        $delete = PorteCidade::where('id_porte', $id_porte)->delete();

        return $delete;
    }
}

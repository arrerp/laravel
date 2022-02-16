<?php

namespace App\Models\DepartamentoPessoal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Util\Util;

class FuncaoUsuario extends Model
{
    protected $table = 'FuncaoUsuario';

    public $timestamps = false;

    protected $fillable = [
        'id_funcao',
        'funcao',
    ];

    static function index($top = 10000){
        $funcaoUsuario = FuncaoUsuario::take($top)
                     ->lock('WITH(NOLOCK)')
                     ->get()
                     ->toArray();

        return $funcaoUsuario;
    }
}

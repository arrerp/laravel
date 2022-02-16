<?php
namespace App\Models\Materiais\Views;

use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class VSaldoItemDispSite extends Model
{
    protected $table = 'VSaldoItemDispSite';

    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'id_produto',
        'qt_saldo',
    ];

    static function getSaldoItem($empresa, $id){
        $item = VSaldoItemDispSite::where('id_empresa', $empresa)
                                  ->where('id_produto', $id)
                                  ->first();

        if(!$item){
            return 0;
        }

        return $item->qt_saldo;
    }
}
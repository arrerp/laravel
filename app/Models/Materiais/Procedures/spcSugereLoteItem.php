<?php

namespace App\Models\Materiais\Procedures;

use Illuminate\Support\Facades\DB;

class spcSugereLoteItem
{
    /**
     * @params id_empresa, id_deposito, id_produto, qt_produto 
     * 
     */
    static function exec($request){
        $query = 'EXEC spcSugereLoteItemDisp ?, ?, ?, ?';

        $result = DB::select($query, $request);

        return $result;
    }
}

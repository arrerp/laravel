<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\TransEstoque;
use App\Util\Util;

class TransEstoqueController extends Controller
{
    public function saldoFisico($id_produto, Request $request){

        $transEstoque = TransEstoque::saldoFisico($request->id_empresa, $request->id_deposito, $id_produto);

        if(isset($transEstoque->qt_fisico)){
            $transEstoque->qt_fisico = Util::formataSaldo($transEstoque->qt_fisico);
        }

        return response()->json($transEstoque, 200);
    }


}

<?php

namespace App\Http\Controllers\Materiais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materiais\PrazoProduto;

class PrazoProdutoController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $prazos = PrazoProduto::index($top);

        return response()->json($prazos, 200);
    }
}

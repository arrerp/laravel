<?php

namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customs\ModelRoutes;

class DynamicController extends Controller
{
    public function getRoutes($model){
        $routes = ModelRoutes::index($model);

        return response()->json($routes, 200);
    }
 
}

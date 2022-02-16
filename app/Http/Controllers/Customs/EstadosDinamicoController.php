<?php
namespace App\Http\Controllers\Customs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customs\EstadosDinamico;
use App\Util\Util;

class EstadosDinamicoController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $data = EstadosDinamico::index($top);

        return response()->json($data, 200);
    }

    public function getColumns(){
        $data = EstadosDinamico::getColumns();

        return response()->json($data, 200);
    }


    public function getById($id){
        $data = EstadosDinamico::getById($id);

        return response()->json($data, 200);
    }

    public function store(Request $request){
        $insert = $this->getFields($request);

        $response = EstadosDinamico::store($insert);
        $httpCode = 200;

        return response()->json($response, $httpCode);
    }

    public function updateItem($id, Request $request){
        $updateFields = $this->getFields($request);

        $update = EstadosDinamico::updateItem($id, $updateFields);

        return response()->json($update, 200);
    }

    public function destroy($id){
        $response = EstadosDinamico::destroy($id);
        $httpCode = 200;

        return response()->json($response, $httpCode);
    }

    private function getFields($request){
        $updateFields = [];
        $request = json_decode($request->getContent());

        foreach($request as $key => $field){
            if($key === 'id'){
                continue;
            }

            $updateFields[$key] = $field;
        }

        return $updateFields;
    }
}


<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Util\Util;


class TipoMovimento extends Model
{
    protected $table = 'TipoMovimento';

    public $timestamps = false;

    protected $fillable = [
        'id_tipoMovimento',
        'descricao',
        'sinal',
        'sinal_estorno',
        'traf_deposito', 
        'obriga_valor', 
        'obriga_obs'
    ];

    static function index($top = 10000, $id_tipoMovimento=null){
        $tipoMovimento = TipoMovimento::take($top)
                                      ->selectRaw("'(' + sinal + ') - ' + descricao AS descricao,
                                                   id_tipoMovimento, 
                                                   sinal,
                                                   sinal_estorno,
                                                   traf_deposito,
                                                   obriga_valor,
                                                   obriga_obs"
                                      )
                                      ->lock('WITH(NOLOCK)')
                                      ->get()
                                      ->toArray();

        return $tipoMovimento;
    }    

    static function getById($id){
        $tipoMovimento = TipoMovimento::where('id_tipoMovimento', $id)
                                      ->lock('WITH(NOLOCK)')
                                      ->first();
        return $tipoMovimento;
    }

    static function store($tipoMovimento)
    {
        if ($tipoMovimento->sinal === 'S'){
            $sinalEstorno = 'E';
        }else{
            $sinalEstorno = 'S';
        }
        $response = false;
        $response = TipoMovimento::insertGetId([
            'descricao'     => $tipoMovimento->descricao,
            'sinal'         => $tipoMovimento->sinal,
            'sinal_estorno' => $sinalEstorno,
            'traf_deposito' => $tipoMovimento->trafDeposito, 
            'obriga_valor'  => $tipoMovimento->obrigaValor, 
            'obriga_obs'    => $tipoMovimento->obrigaObs 
        ]);

        return $response;
    }

    static function updateItem($id_tipoMovimento, $tipoMovimento){
        if ($tipoMovimento->sinal === 'S'){
            $sinalEstorno = 'E';
        }else{
            $sinalEstorno = 'S';
        };

        $update = false;
            
        $update = TipoMovimento::where('id_tipoMovimento', $id_tipoMovimento)->update([
            'descricao'     => $tipoMovimento->descricao,
            'sinal'         => $tipoMovimento->sinal,
            'sinal_estorno' => $sinalEstorno,
            'traf_deposito' => $tipoMovimento->trafDeposito,
            'obriga_valor'  => $tipoMovimento->obrigaValor, 
            'obriga_obs'    => $tipoMovimento->obrigaObs 
        ]);

        return $update;
    }

    /**
     * 
     * @var int
     * @return mixed
     * 
    */
    static function deleteItem($id_tipoMovimento){
        try {
            $delete = TipoMovimento::where('id_tipoMovimento', $id_tipoMovimento)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    }

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }    

    private function requestValidate($request){
        $request->validate([
            'descricao'    => ['required'],
            'sinal'        => ['required'],
            'obrigaValor'  => ['required'],
            'obrigaObs'    => ['required']
        ]);

        return true;
    }   
}

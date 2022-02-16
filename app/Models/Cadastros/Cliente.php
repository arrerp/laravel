<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'Cliente'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro'     ,
        'permite_email'   ,
        'permite_sms'     ,
        'permite_whats'   ,
        'restricao_venda' ,
        'consumidor_final',
        'charge_back'     
    ];

    static function getById($id){
        $clientes = Cliente::where('id_cadastro', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $clientes;
    }

    static function store($id_cadastro, $cliente)
    {
        $response = false;

        $response = Cliente::create([
            'id_cadastro'      => $id_cadastro             ,
            'permite_sms'      => $cliente->permiteSms     ,
            'permite_whats'    => $cliente->permiteWhats   ,
            'restricao_venda'  => $cliente->restricaoVenda ,
            'consumidor_final' => $cliente->consumidorFinal,
            'charge_back'      => $cliente->chargeBack     ,
            'permite_email'    => $cliente->permiteEmail   
        ]);

        return $response;
    }

    static function updateItem($id_cadastro, $cliente){
        $update = false;
            
        $update = Cliente::where('id_cadastro', $id_cadastro)->update([
            'permite_email'    => $cliente->permiteEmail   ,
            'permite_sms'      => $cliente->permiteSms     ,
            'permite_whats'    => $cliente->permiteWhats   ,
            'restricao_venda'  => $cliente->restricaoVenda ,
            'consumidor_final' => $cliente->consumidorFinal,
            'charge_back'      => $cliente->chargeBack
        ]);

        return $update;
    }

    static function deleteItem($id_cadastro){
        try {
            $delete = Cliente::where('id_cadastro', $id_cadastro)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }       
}

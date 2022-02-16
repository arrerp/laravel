<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class TabelaFrete extends Model
{
    protected $table = 'TabelaFrete'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_arquivo', 'cep_inicial', 'cep_final' , 'cd_estado' , 'cidade'    , 'prazo_ent' , 'fator_kg'  , 'ad_valorem', 'vl_gris'   , 'vl_imposto',
        'peso_0_250'   , 'peso_0_300' , 'peso_0_500', 'peso_0_750', 'peso_1000' , 'peso_2000' , 'peso_3000' , 'peso_4000' , 'peso_5000' , 'peso_6000' ,
        'peso_7000'    , 'peso_8000'  , 'peso_9000' , 'peso_10000', 'peso_11000', 'peso_12000', 'peso_13000', 'peso_14000', 'peso_15000', 'peso_16000',
        'peso_17000'   , 'peso_18000' , 'peso_19000', 'peso_20000', 'peso_21000', 'peso_22000', 'peso_23000', 'peso_24000', 'peso_25000', 'peso_26000',
        'peso_27000'   , 'peso_28000' , 'peso_29000', 'peso_30000', 'id_usuario', 'situacao'  , 'dt_processo'
    ];

    static function index($id_arquivo){
        $tabelaFrete = TabelaFrete::where('id_arquivo', $id_arquivo)
                                        ->lock('WITH(NOLOCK)')
                                        ->selectRaw("TabelaFrete.*")
                                        ->orderBy('TabelaFrete.id_tabFrete', 'asc')
                                        ->get();
        return $tabelaFrete;
    }

    static function getById($id){
        $tabelaFrete = TabelaFrete::where('id_tabFrete', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $tabelaFrete;
    }

    static function store($idArquivo, $tabelaFrete)
    {
        $response = false;

        $response = TabelaFrete::create([
            'id_arquivo'    => $idArquivo,
            'cep_inicial'   => $tabelaFrete->cep_inicial,
            'cep_final'     => $tabelaFrete->cep_final,
            'cd_estado'     => $tabelaFrete->cd_estado,
            'cidade'        => $tabelaFrete->cidade,
            'prazo_ent'     => $tabelaFrete->prazo_ent,
            'fator_kg'      => $tabelaFrete->fator_kg,
            'ad_valorem'    => $tabelaFrete->ad_valorem,
            'vl_gris'       => $tabelaFrete->vl_gris,
            'vl_imposto'    => $tabelaFrete->vl_imposto,
            'peso_0_250'    => $tabelaFrete->peso_0_250,
            'peso_0_300'    => $tabelaFrete->peso_0_300,
            'peso_0_500'    => $tabelaFrete->peso_0_500,
            'peso_0_750'    => $tabelaFrete->peso_0_750,
            'peso_1000'     => $tabelaFrete->peso_1000,
            'peso_2000'     => $tabelaFrete->peso_2000,
            'peso_3000'     => $tabelaFrete->peso_3000,
            'peso_4000'     => $tabelaFrete->peso_4000,
            'peso_5000'     => $tabelaFrete->peso_5000,
            'peso_6000'     => $tabelaFrete->peso_6000,
            'peso_7000'     => $tabelaFrete->peso_7000,
            'peso_8000'     => $tabelaFrete->peso_8000,
            'peso_9000'     => $tabelaFrete->peso_9000,
            'peso_10000'    => $tabelaFrete->peso_10000,
            'peso_11000'    => $tabelaFrete->peso_11000,
            'peso_12000'    => $tabelaFrete->peso_12000,
            'peso_13000'    => $tabelaFrete->peso_13000,
            'peso_14000'    => $tabelaFrete->peso_14000,
            'peso_15000'    => $tabelaFrete->peso_15000,
            'peso_16000'    => $tabelaFrete->peso_16000,
            'peso_17000'    => $tabelaFrete->peso_17000,
            'peso_18000'    => $tabelaFrete->peso_18000,
            'peso_19000'    => $tabelaFrete->peso_19000,
            'peso_20000'    => $tabelaFrete->peso_20000,
            'peso_21000'    => $tabelaFrete->peso_21000,
            'peso_22000'    => $tabelaFrete->peso_22000,
            'peso_23000'    => $tabelaFrete->peso_23000,
            'peso_24000'    => $tabelaFrete->peso_24000,
            'peso_25000'    => $tabelaFrete->peso_25000,
            'peso_26000'    => $tabelaFrete->peso_26000,
            'peso_27000'    => $tabelaFrete->peso_27000,
            'peso_28000'    => $tabelaFrete->peso_28000,
            'peso_29000'    => $tabelaFrete->peso_29000,
            'peso_30000'    => $tabelaFrete->peso_30000,
            'id_usuario'    => auth()->user()->id,
            'situacao'      => 'A',
            'dt_processo'   => Now('America/Fortaleza') 
        ]);

        return $response;
    }

    static function updateItem($id_tabFrete, $tabelaFrete){
        $update = false;
            
        $update = TabelaFrete::where('id_tabFrete', $id_tabFrete)->update([
            'cep_inicial'   => $tabelaFrete->cepInicial,
            'cep_final'     => $tabelaFrete->cepFinal,
            'cd_estado'     => $tabelaFrete->cdEstado,
            'cidade'        => $tabelaFrete->cidade,
            'prazo_ent'     => $tabelaFrete->prazoEnt,
            'fator_kg'      => $tabelaFrete->fatorKg,
            'ad_valorem'    => $tabelaFrete->adValorem,
            'vl_gris'       => $tabelaFrete->vlGris,
            'vl_imposto'    => $tabelaFrete->vlImposto,
            'peso_0_250'    => $tabelaFrete->peso0250,
            'peso_0_300'    => $tabelaFrete->peso0300,
            'peso_0_500'    => $tabelaFrete->peso0500,
            'peso_0_750'    => $tabelaFrete->peso0750,
            'peso_1000'     => $tabelaFrete->peso1000,
            'peso_2000'     => $tabelaFrete->peso2000,
            'peso_3000'     => $tabelaFrete->peso3000,
            'peso_4000'     => $tabelaFrete->peso4000,
            'peso_5000'     => $tabelaFrete->peso5000,
            'peso_6000'     => $tabelaFrete->peso6000,
            'peso_7000'     => $tabelaFrete->peso7000,
            'peso_8000'     => $tabelaFrete->peso8000,
            'peso_9000'     => $tabelaFrete->peso9000,
            'peso_10000'    => $tabelaFrete->peso10000,
            'peso_11000'    => $tabelaFrete->peso11000,
            'peso_12000'    => $tabelaFrete->peso12000,
            'peso_13000'    => $tabelaFrete->peso13000,
            'peso_14000'    => $tabelaFrete->peso14000,
            'peso_15000'    => $tabelaFrete->peso15000,
            'peso_16000'    => $tabelaFrete->peso16000,
            'peso_17000'    => $tabelaFrete->peso17000,
            'peso_18000'    => $tabelaFrete->peso18000,
            'peso_19000'    => $tabelaFrete->peso19000,
            'peso_20000'    => $tabelaFrete->peso20000,
            'peso_21000'    => $tabelaFrete->peso21000,
            'peso_22000'    => $tabelaFrete->peso22000,
            'peso_23000'    => $tabelaFrete->peso23000,
            'peso_24000'    => $tabelaFrete->peso24000,
            'peso_25000'    => $tabelaFrete->peso25000,
            'peso_26000'    => $tabelaFrete->peso26000,
            'peso_27000'    => $tabelaFrete->peso27000,
            'peso_28000'    => $tabelaFrete->peso28000,
            'peso_29000'    => $tabelaFrete->peso29000,
            'peso_30000'    => $tabelaFrete->peso30000,
            'id_usuario'    => auth()->user()->id,
            'situacao'      => $tabelaFrete->situacao,
            'dt_processo'   => Now('America/Fortaleza')

        ]);

        return $update;
    }

    static function deleteItem($id_tabFrete){
        try {
            $delete = TabelaFrete::where('id_tabFrete', $id_tabFrete)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }  
    
    static function deleteFile($id_arquivo){
        try {
            $delete = TabelaFrete::where('id_arquivo', $id_arquivo)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }       
    
    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }        
}

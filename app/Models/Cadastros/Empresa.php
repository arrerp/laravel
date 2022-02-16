<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Empresa extends Model
{
    protected $table = 'Empresa';

    public $timestamps = false;

    protected $fillable = [
        'id_empresa', 
        'emp_filial', 
        'situacao'
    ];


    static function getById($id){
        $empresas = Empresa::where('id_empresa', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $empresas;
    }    
    static function index($top = 1000){
        $empresas = Empresa::join('Cadastro', 'Cadastro.id_cadastro', '=', 'Empresa.id_empresa')
                           ->take($top)
                           ->lock('WITH(NOLOCK)')
                           ->selectRaw("Empresa.id_empresa, 
                                        Cadastro.fantasia , 
                                        Empresa.situacao  ,
                                        CASE WHEN Empresa.emp_filial = 'S' THEN 'Sim' ELSE 'Não' END emp_filial")
                           ->get();

        return $empresas;
    }

    static function getWhere($empresa){
        $empresas = Empresa::where('id_empresa', $empresa)->lock('WITH(NOLOCK)')->get();

        return $empresas;
    }

    static function store($id_empresa, $empresa)
    {
        $response = false;

        if(!Empresa::existsItem($id_empresa)){
            $response = Empresa::create([
                'id_empresa'=> $id_empresa,
                'situacao'  => $empresa->situacao,
                'emp_filial'=> $empresa->empFilial
            ]);
        }

        return $response;
    }

    static function existsItem($id_empresa)
    {
        return Empresa::where('id_empresa', $id_empresa)->exists();
    }

    static function updateItem($id_empresa, $empresa){
        $update = false;

        $update = Empresa::where('id_empresa', $id_empresa)
                         ->update([
                            'emp_filial' => $empresa->empFilial, 
                            'situacao'   => $empresa->situacao
                         ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Empresa::where('id_empresa', $id)
                         ->update([
                            'situacao' => 'X'
                         ]);

        return $update;
    }      

    static function deleteItem($id_empresa){
        try {
            $delete = Empresa::where('id_empresa', $id_empresa)->delete();
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

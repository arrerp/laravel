<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Pessoal extends Model
{
    protected $table = 'CadastroPessoal';

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'sexo',
        'dt_nascimento',
        'estado_civil',
        'nome_conjuge',
        'nacionalidade',
        'renda_familiar',
        'referencia',
        'observacoes'
    ];


    static function index($id, $top = 10000){
        $pessoal = Pessoal::join('Cadastro' , 'Pessoal.id_cadastro', '=', 'Cadastro.id_cadastro')
                            ->take($top)
                            ->lock('WITH(NOLOCK)')
                            ->selectRaw("sexo,
                                         dt_nascimento,
                                         estado_civil,
                                         nome_conjuge,
                                         nacionalidade,
                                         renda_familiar,
                                         referencia,
                                         observacoes")
                            ->where('id_cadastro', $id) 
                            ->get();
        return $pessoal;
    }    

    static function getById($id){
        $pessoal = Pessoal::where('id_cadastro', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $pessoal;
    }

    static function store($id_cadastro, $pessoal)
    {
        $response = false;
        $response = Pessoal::insertGetId([
            'id_cadastro'    => $id_cadastro           ,
            'sexo'           => $pessoal->sexo         ,
            'dt_nascimento'  => $pessoal->dtNascimento ,
            'estado_civil'   => $pessoal->estadoCivil  ,
            'nome_conjuge'   => $pessoal->nomeConjuge  ,
            'nacionalidade'  => $pessoal->nacionalidade,
            'renda_familiar' => Util::formataMoedaDB($pessoal->rendaFamiliar),
            'referencia'     => $pessoal->referencia   ,
            'observacoes'    => $pessoal->observacoes
        ]);

        return $response;
    }

    static function updateItem($id_cadastro, $pessoal){
        $update = false;
            
        $update = Pessoal::where('id_cadastro', $id_cadastro)->update([
            'id_cadastro'    => $id_cadastro           ,
            'sexo'           => $pessoal->sexo         ,
            'dt_nascimento'  => $pessoal->dtNascimento ,
            'estado_civil'   => $pessoal->estadoCivil  ,
            'nome_conjuge'   => $pessoal->nomeConjuge  ,
            'nacionalidade'  => $pessoal->nacionalidade,
            'renda_familiar' => Util::formataMoedaDB($pessoal->rendaFamiliar),
            'referencia'     => $pessoal->referencia   ,
            'observacoes'    => $pessoal->observacoes
        ]);

        return $update;
    }

    static function deleteItem($id_cadastro){
        
        $delete = Pessoal::where('id_cadastro', $id_cadastro)->delete();

        return $delete;
    }

    private function requestValidate($request){
        $request->validate([
            'sexo'    => ['required']
        ]);


        return true;
    }    

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }       
 
}

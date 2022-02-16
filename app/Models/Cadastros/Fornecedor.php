<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = 'Fornecedor'
    ;

    public $timestamps = false;

    protected $fillable = [
       'id_cadastro',
       'simples_federal',
       'opt_subst_trib_pis_cof',
       'subs_trib_icms',
       'nao_contrib_icms',
       'prod_rural'
    ];

    static function getById($id){
        $fornecedor = Fornecedor::where('id_cadastro', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $fornecedor;
    }

    static function store($id_cadastro, $fornecedor)
    {

        $response = false;

        $response = Fornecedor::create([
                                         'id_cadastro'            => $id_cadastro,
                                         'simples_federal'        => $fornecedor->simplesFederal,
                                         'opt_subst_trib_pis_cof' => $fornecedor->optSubstTribPisCof,
                                         'subs_trib_icms'         => $fornecedor->subsTribIcms,
                                         'nao_contrib_icms'       => $fornecedor->naoContribIcms,
                                         'prod_rural'             => $fornecedor->prodRural
        ]);

        return $response;
    }

}

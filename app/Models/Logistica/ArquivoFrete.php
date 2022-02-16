<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class ArquivoFrete extends Model
{
    protected $table = 'ArquivoFrete'
    ;

    public $timestamps = false;

    protected $fillable= [
        'nome_arquivo',
        'dt_importacao',
        'id_usuario',
        'situacao',
        'id_tipoTransp'
    ];


    static function index($top = 100){
        $arquivoFrete = ArquivoFrete::join('TipoTransporte', 'ArquivoFrete.id_tipoTransp', '=', 'TipoTransporte.id_tipoTransp')
                                        ->join('Users', 'ArquivoFrete.id_usuario', '=', 'users.id')
                                        ->take($top)
                                        ->lock('WITH(NOLOCK)')
                                        ->selectRaw("ArquivoFrete.id_arquivo, 
                                                     ArquivoFrete.nome_arquivo, 
                                                     ArquivoFrete.dt_importacao, 
                                                     TipoTransporte.descricao, 
                                                     Users.name, 
                                                     ArquivoFrete.situacao, 
                                                     ISNULL((SELECT COUNT(*) 
                                                              FROM TabelaFrete WITH(NOLOCK) 
                                                             WHERE ArquivoFrete.id_arquivo = TabelaFrete.id_arquivo
                                                               AND TabelaFrete.situacao = 'A'), 0) qt_linhas")
                            ->orderBy('ArquivoFrete.dt_importacao', 'asc')
                            ->get();
        return $arquivoFrete;
    }

    static function getById($id){
        $arquivoFrete = ArquivoFrete::where('id_arquivo', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $arquivoFrete;
    }

    static function store($arquivoFrete)
    {
        $response = false;

        $response = ArquivoFrete::insertGetId([
            'nome_arquivo'  => $arquivoFrete->nomeArquivo,
            'dt_importacao' => Now('America/Fortaleza'),
            'id_usuario'    => auth()->user()->id,
            'situacao'      => 'A',
            'id_tipoTransp' => $arquivoFrete->idTipoTransp
        ]);

        return $response;
    }

    static function updateItem($id_arquivo, $arquivoFrete){
        $update = false;
            
        $update = ArquivoFrete::where('id_arquivo', $arquivoFrete)->update([
            'dt_importacao' => Now('America/Fortaleza'),
            'id_usuario'    => auth()->user()->id,
            'situacao'      => $arquivoFrete->situacao
        ]);

        return $update;
    }

    static function deleteItem($id_arquivo){
        try {
            $delete = ArquivoFrete::where('id_arquivo', $id_arquivo)->delete();
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

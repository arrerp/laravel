<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\GrupoUsuario;
use App\Models\Admin\Usuario;
use App\Models\DepartamentoPessoal\FuncaoUsuario;
use App\Models\Cadastros\Empresa;
use App\Util\Util;

class UsuarioController extends Controller
{
    public function index(Request $request){
        $top = 10000;

        if($request->top){
            $top = $request->top;
        }

        $usuarios = Usuario::index($top);

        return response()->json($usuarios, 200);
    }

    public function getPageInfo(Request $request){
        $grupoUsuario  = GrupoUsuario::index();
        $funcaoUsuario = FuncaoUsuario::index();
        $supervisor    = Usuario::index();
        $empresas      = Empresa::index();

        $response = [
            'grupoUsuario'  => Util::toSelectKeys($grupoUsuario , 'grupo', 'id_grupo' ),
            'funcaoUsuario' => Util::toSelectKeys($funcaoUsuario, 'funcao', 'id_funcao' ),
            'supervisor'    => Util::toSelectKeys($supervisor, 'name', 'id' ),
            'empresas'      => Util::toSelectKeys($empresas, 'fantasia', 'id_empresa' ),
        ];

        return response()->json($response, 200);        
    }    


    public function getById($id){
        $usuario = Usuario::getById($id);

        if ($usuario->url_img){
            $usuario->url_img = $this->getPhoto($usuario->id, $usuario->url_img);
        } 
        
        $customVars = [
            'true' => [
                'S'
            ],
            'false' => [
                'N'
            ]
        ];

        $usuario = Util::varcharToBoolean(Util::toArray($usuario), true, $customVars);

        return response()->json($usuario, 200);
    }

    public function store(Request $request){
        $usuario  = $this->serializeRequest($request);
        $response = Usuario::store($usuario);
        $httpCode = 200;
        if(!$response){
            $response = [
                'errors' => [
                    'name' => ['Registro já cadastrado!']
                ]
            ];
            $httpCode = 402;
        }

        return response()->json($response, $httpCode);
    }    


    public function delete($id){
        $response = Usuario::deleteItem($id);
        $httpCode = 200;

        if(isset($response->errorInfo)){
            $httpCode = 400;
            if ($response->errorInfo[0]==23000){
                $response = Usuario::updateSituacao($id);
                $httpCode = 200;
            }
        }else if(!$response){
            $httpCode = 400;
            $response = [
                'errors' => [
                    'usuario' => ['Não foi possível excluir o usuário!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }   
    
    public function update($id, Request $request){
        $usuario = $this->serializeRequest($request);

        $response = Usuario::updateItem($id, $usuario);
        $httpCode = 200;

        if(!$response){
            $httpCode = 402;
            $response = [
                'errors' => [
                    'usuario' => ['Não foi possível alterar o Usuário!']
                ]
            ];
        }

        return response()->json($response, $httpCode);
    }    

    private function serializeRequest($request){
        $requestArray = json_decode($request->getContent(), true);

        $usuario = Util::booleanToVarchar($requestArray);

        return $usuario;
    }      

    private function requestValidate($request){
        $request->validate([
            'name'           => ['required'],
            'email'          => ['required'],
            'idGrupoUsuario' => ['required'],
            'idFuncao'       => ['required'],
            'idEmpresa'      => ['required'],
        ]);

        return true;
    }    


    public function getPhoto($id, $url_img){
        $basePath = 'C:/Intel/ERP/';
        $imagesBase64 = [];

        $path = $basePath . $url_img;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        if($data){
            $imgInfo = [
                'id' =>  $id,
                'src' => 'data:image/' . $type . ';base64,' . base64_encode($data)
            ];
        }
        else {
            $imgInfo = $path;
        }


        $imagesBase64[] = $imgInfo;

        return $imagesBase64;
    }    

    public function storePhoto(Request $request){
        if(!$request->hasFile('images')){
            return;
        }

        $imagens = $request->file('images');

        $path = '/' . $request->id; 
        // Nome original do arquivo
        $nome_arquivo = $imagens->getClientOriginalName();
        $caminho = 'imagens/usuario' . $path . '/' . $nome_arquivo;
        // Método para salvar o arquivo no disco
        $imagens->storeAs($path, $nome_arquivo, 'imgUsuario');
        $res = Usuario::updatePhoto($request->id, $caminho);
        return response()->json($res, 200);
    }

}
